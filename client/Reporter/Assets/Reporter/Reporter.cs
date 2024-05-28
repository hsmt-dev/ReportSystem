using UnityEngine;
using UnityEngine.Networking;
using System;
using System.Collections;
using System.Collections.Generic;

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


    public IEnumerator Send( string url, string title, string json, Action<string> success=null )
    {
        _contents = new Dictionary<string, string>();

        AddContent("title", title);
        AddContent("json_data", json);

        yield return CaptureScreenShot();

        var webRequest = UnityWebRequest.Post(url, BuildForm());
        webRequest.timeout = 30;

        yield return webRequest.SendWebRequest();

        switch( webRequest.result )
        {
            case UnityWebRequest.Result.InProgress:
                Debug.Log( "Requesting wait..." );
                break;
            case UnityWebRequest.Result.Success:
                var key = webRequest.downloadHandler.text;
                success?.Invoke( key );
                Debug.Log("Form upload complete! : "+key);
                break;
            case UnityWebRequest.Result.ConnectionError:
            case UnityWebRequest.Result.ProtocolError:
            case UnityWebRequest.Result.DataProcessingError:
            default:
                Debug.LogErrorFormat("[{0}]{1}", webRequest.responseCode, webRequest.error);
                break;
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
}
