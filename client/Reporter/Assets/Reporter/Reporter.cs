using UnityEngine;
using UnityEngine.Networking;
using System;
using System.Collections;
using System.Collections.Generic;
using System.Threading;
using System.Globalization;
using System.IO;

public class Reporter
{
    private static Reporter _instance;
    public static Reporter Instance { 
        get { 
            return _instance ?? (_instance = new Reporter()); 
        }
    }

    private Dictionary<string, string> _contents = null;
    private byte[] _screenShotData = null;


    public IEnumerator Send( string url, string title, Action<string> success=null )
    {
        _contents = new Dictionary<string, string>();

        AddContent("title", title);

        AddSystemInfo();

        yield return CaptureScreenShot();

        var webRequest = UnityWebRequest.Post(url, BuildForm());
        webRequest.timeout = 30;

        yield return webRequest.SendWebRequest();

        if (webRequest.isNetworkError || webRequest.isHttpError) {
            Debug.LogErrorFormat("[{0}]{1}", webRequest.responseCode, webRequest.error);
        } else {
            success?.Invoke( _contents["date_time"] );
            Debug.Log("Form upload complete!");
        }

        _contents.Clear();
    }

    private IEnumerator CaptureScreenShot()
    {
        yield return new WaitForEndOfFrame();
        var tex = new Texture2D(Screen.width, Screen.height, TextureFormat.RGB24, false);
        tex.ReadPixels(new Rect(0, 0, Screen.width, Screen.height), 0, 0);
        tex.Apply();
        _screenShotData = tex.EncodeToPNG();
        UnityEngine.Object.Destroy(tex);
    }

    private List<IMultipartFormSection> BuildForm()
    {
        var form = new List<IMultipartFormSection>();
        foreach (var item in _contents) {
            form.Add(new MultipartFormDataSection(item.Key, item.Value));
        }
        // 
        var filename = long.Parse(DateTime.Now.ToString("yyyyMMddHHmmss")) + ".png";
        form.Add(new MultipartFormFileSection("image", _screenShotData, Path.GetFileName(filename), "image/png"));
        return form;
    }

    private void AddSystemInfo()
    {
        const uint mega = 1024 * 1024;

        AddContent("date_time", GetCurrentUnixTime().ToString());
        AddContent("operating_system", SystemInfo.operatingSystem);
        AddContent("device_model", SystemInfo.deviceModel);
        AddContent("system_memory_size", (SystemInfo.systemMemorySize * mega).ToString());
        AddContent("use_memory_size", GC.GetTotalMemory(false).ToString());
//        AddContent("Log", logData);
//        AddContent("ScreenShotBase64", screenShotData);
    }

    /// <summary> 送信情報に追加 </summary>
    private void AddContent(string key, string value)
    {
        if (_contents == null) { return; }
        if (string.IsNullOrEmpty(value))
        {
            value = "---";
        }
//        value = aesCryptoKey != null ? value.Encrypt(aesCryptoKey) : value;
        _contents.Add(key, value);
    }

    private int GetCurrentUnixTime()
    {
        var unixTimestamp = (int)(DateTime.UtcNow.Subtract(new DateTime(1970, 1, 1))).TotalSeconds;
        return unixTimestamp;
    }
}
