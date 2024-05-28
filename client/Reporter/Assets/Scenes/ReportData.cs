
using System;
using UnityEngine;
using System.Collections;
using System.Collections.Generic;
using System.Threading;
using System.Globalization;

[Serializable]
public class ReportData
{
    public string TerminalTime = "";
    public string OperatinSystem = "";
    public string DeviceMode = "";
    public string SystemMemorySize = "";
    public string UseMemorySize = "";

    public ReportData()
    {
        Refresh();
    }

    public void Refresh()
    {
        const uint mega = 1024 * 1024;

        // 現在のUTC時間を取得
        DateTime utcNow = DateTime.UtcNow;
        // タイムゾーンを指定して変換
        TimeZoneInfo timeZone = TimeZoneInfo.FindSystemTimeZoneById("Tokyo Standard Time");
        DateTime localTime = TimeZoneInfo.ConvertTimeFromUtc(utcNow, timeZone);

        TerminalTime = localTime.ToString("yyyy-MM-dd HH:mm:ss");
        OperatinSystem = SystemInfo.operatingSystem;
        DeviceMode = SystemInfo.deviceModel;
        SystemMemorySize = (SystemInfo.systemMemorySize * mega).ToString();
        UseMemorySize = GC.GetTotalMemory(false).ToString();
    }
}
