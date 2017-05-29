<html>
    <head>
        <title>周大法视频播放器</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="style.css" />
        <?php $VideoListJSON = getVideoListJSON(); ?>
    </head>
    <body>
        <div align="center">
            <div id="video">
                <spam id='myplayer'></spam>
            </div>
            <div id="list">
                <div id="folder">
                    <select id = "FolderSelect" name="FolderSelect" onchange="OnFolderSelectChanged()"></select>
                </div>
                <div>
                    <div id="search">
                        <spam id="searchBoxSpam">
                            <input type="text" id="SearchWord" name="SearchWord" onchange="OnSearch()">
                        </spam>
                        <spam id="clearButtonSpam">
                            <button type="button" id="clearButton" onclick="OnFolderSelectChanged()">清除</button>
                        </spam>
                    </div>
                    <div id="result"></div>
                </div>
            </div>
        </div> 
    </body>
</html>

<script src="/jwplayer/jwplayer.js"></script>
<script src="/jwplayer/jwplayer.flash.swf"></script>
<script>jwplayer.key="iP+vLYU9H5KyhZeGt5eVuJJIoULUjltoaMeHXg==";</script>
<script type='text/javascript'>
    var VideoList = JSON.parse('<?php echo getVideoListJSON(); ?>');
    var FolderSelect = document.getElementById("FolderSelect");
    for(Folder in VideoList) {
        var y=document.createElement('option');
        y.text=Folder;
        FolderSelect.add(y, null)
    }
    OnFolderSelectChanged();
    
    function OnFolderSelectChanged() {
        var SearchWordBox = document.getElementById("SearchWord");
        SearchWordBox.value = "";
        var FolderSelect = document.getElementById("FolderSelect");
        var ResultList = document.getElementById("result");
        while(ResultList.hasChildNodes()) {
            ResultList.removeChild(ResultList.firstChild);
        }
        var FolderName = FolderSelect.options[FolderSelect.selectedIndex].text;
        for(i = 0; i < VideoList[FolderName].length; i++) {
            var FileName = VideoList[FolderName][i];
            AddButtonToResultList(FolderName, FileName);
        }
        if(!ResultList.hasChildNodes())
            ResultList.appendChild(document.createTextNode("文件夹为空"));
    }
    function SetVideo(VideoName) {
        jwplayer('myplayer').setup({file: VideoName,width: '640',height: '480'});
    }
    function OnSearch() {
        var SearchWordBox = document.getElementById("SearchWord");
        var SearchWord = SearchWordBox.value;
        var FolderSelect = document.getElementById("FolderSelect");
        var ResultList = document.getElementById("result");
        while(ResultList.hasChildNodes()) {
            ResultList.removeChild(ResultList.firstChild);
        }
        var FolderName = FolderSelect.options[FolderSelect.selectedIndex].text;
        for(i = 0; i < VideoList[FolderName].length; i++) {
            var FileName = VideoList[FolderName][i];
            if(FileName.match(SearchWord) == null)
                continue;
            AddButtonToResultList(FolderName, FileName);
        }
        if(!ResultList.hasChildNodes())
            ResultList.appendChild(document.createTextNode("搜索无结果"));
    }
    function AddButtonToResultList(FolderName, FileName) {
        var FileNameWithPath = FolderName + "/" + FileName;
        var ResultList = document.getElementById("result");
        var Div = document.createElement("div");
        var Button = document.createElement("button");
        Button.id = FileNameWithPath;
        //Button.name = FileName;
        Button.innerHTML = FileName;
        Button.onclick = function(){SetVideo(this.id);};
        Div.appendChild(Button);
        Div.className = "Videos";
        ResultList.appendChild(Div);
    }
</script>
<?php
    function getVideoListJSON() {
        $json = "{";
        $dirs = getDirs("video");  
        foreach ($dirs as $dir) {
            $json .= "\"".iconv("GBK", "UTF-8", $dir)."\":[";
            $files = getFiles($dir);
            foreach ($files as $file) {
                $json .= "\"".iconv("GBK", "UTF-8", $file)."\","; 
            }
            $json = substr($json,0,strlen($json) - 1);
            $json .= "],";
        }
        $json = substr($json,0,strlen($json) - 1);
        $json .= "}";
        return $json;
    }

    function getDirs($BaseDir) {
        if(!is_dir($BaseDir)) 
            die('视频目录不存在');
        
        $dirs =  array();         
                
        $dp = dir($BaseDir);  
        while ($file = $dp ->read()){  
            if($file !="." && $file !=".."){  
                if(is_dir("./video/".$file))
                    $dirs[] = "./video/".$file;
            }  
        }  
        $dp ->close();
        return $dirs;  
    }
    
    function getFiles($Dir) {
        $files =  array();  
                
        $dp = dir($Dir);  
        while ($file = $dp ->read()){  
            if($file !="." && $file !=".."){  
                if(is_file($Dir."/".$file))
                    $files[] = $file;
            }  
        }  
        $dp ->close();
        return $files;  
    }
?>