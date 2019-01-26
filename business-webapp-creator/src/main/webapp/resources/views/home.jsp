<div class="templatemo-flex-row">
    <div class="templatemo-sidebar">
        <header class="templatemo-site-header">
            <div class="square"></div>
            <h1>Visual Executor</h1>
        </header>
        <div class="profile-photo-container">
            <img src="./resources/images/profile-service.jpg" alt="Profile Photo" class="img-responsive">
            <div class="profile-photo-overlay"></div>
        </div>
        <div class="mobile-menu-icon">
            <i class="fa fa-bars"></i>
        </div>
        <nav class="templatemo-left-nav">
            <ul class="uppercase">
                <li ng-repeat="ws in workspaces" data-workspace="{{ ws.workspaceID}}"
                    ng-click="startWorkspace(ws)">
                    <a href=""><i class="fa fa-home fa-fw"></i>{{ ws.workspaceName}}</a>
                </li>
            </ul>
        </nav>
    </div>  
    <!-- Main content -->
    <div class="templatemo-content col-1 light-gray-bg">
        <div class="templatemo-top-nav-container">
            <div class="row">
                <nav class="templatemo-top-nav col-lg-12 col-md-12">
                    <ul class="text-uppercase">
                        <li><a href ng-click="showDeployWarWorkspace()">Deploy War AS WorkSpace</a></li>
                        <li><a href ng-click="showCreateNewWorkspace()">New Workspace</a></li>
                    </ul>
                </nav>
            </div>
        </div>

        <div class="templatemo-content-container">
            <div ng-show="tableViewGroupProcess" ng-include="'./resources/views/workspace.jsp'"></div>
        </div>
    </div>
</div>
<link href="resources/css/login.css"  rel="stylesheet" type="text/css"  />
<link href="resources/css/fileSubmit.css"  rel="stylesheet" type="text/css"  />
<div id="idmodel" class="modal modelDialog">

    <form class="modal-content animate">
        <div class="imgcontainer">
            <span onclick="document.getElementById('idmodel').style.display = 'none'" class="close" title="Close Modal">&times;</span>
            <!--<img src="img_avatar2.png" alt="Avatar" class="avatar">-->
            <label class="avatar">Create New Workspace</label>
        </div>

        <div class="container">
            <label for="nameworkspace"><b>Name Workspace</b></label>
            <input type="text" placeholder="Enter Workspace" ng-model="nameworkspace" name="nameworkspace" required>
            <label for="psw"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="psw" required>
            <div ng-show="deployfile" class="custom-file-upload">
                <input type="file" id="myfile"  onchange='selectFile(event)'>
            </div>
        </div>

        <div class="container" >
            <button type="button" onclick="document.getElementById('idmodel').style.display = 'none'" class="cancelbtn">Cancel</button>
            <button type="button" class="sucessbtn" ng-click="ActionWorkspace()">Create</button>
        </div>
    </form>
</div>