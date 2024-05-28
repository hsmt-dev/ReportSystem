using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class SceneMain : MonoBehaviour
{
    string _title = "";
    string _url = "";
    string _json = "";

    void Start()
    {
    }

    private void OnGUI() 
    {
        GUILayout.Label("Title");
        _title = GUILayout.TextField(_title, 64, GUILayout.Width(400));
        if (GUILayout.Button("Send"))
        {
            // 表示したい内容のjsonを生成する
            ReportData reportData = new ReportData();
            _json = JsonUtility.ToJson( reportData );

            _url="";
            StartCoroutine( Reporter.Instance.Send( "http://192.168.49.184:8080/report/upload.php", _title, _json, (id) => {
                _url = "http://192.168.49.184:8080/report/post.php?id="+id;
            } ) );
/*
            StartCoroutine( Reporter.Instance.Send( "http://192.168.0.110:8888/report/upload.php", _title, (id) => {
                _url = "http://192.168.0.110:8888/report/post.php?id="+id;
            } ) );
*/
        }

        if(_url!="") 
        {
            GUILayout.Space(16);
            GUILayout.Label("URL");
            GUILayout.TextField(_url , 64, GUILayout.Width(400));
            if (GUILayout.Button("Open"))
            {
                Application.OpenURL(_url);
            }
        }


    }

}
