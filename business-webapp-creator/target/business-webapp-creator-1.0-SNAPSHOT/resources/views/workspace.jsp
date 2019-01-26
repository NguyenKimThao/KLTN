<div>
    <div class="templatemo-flex-row flex-content-row">
        <div class="col-1">
            <div class="panel panel-default templatemo-content-widget white-bg no-padding templatemo-overflow-hidden"
                 style="width:100%">
                <div class="panel-heading templatemo-position-relative row">
                    <div class="col-md-2">
                        <i ng-show="prevProcessTab" class="glyphicon glyphicon-circle-arrow-left" style="float: left; font-size: 25px"
                           ng-click="prevProcessTab(groupProcessID)"></i>
                    </div>
                    <div class="col-md-5">
                        <h2 class="text-uppercase" style="text-align : center">Workspace {{workspace.workspaceName}}</h2>
                    </div>
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-6" ng-click="chooseDeployBPMN()">
                                <a class="templatemo-white-button"  >DEPLOY BPMN</a>
                            </div>
                            <div class="col-md-6" ng-click="chooseDeployWordpress()">
                                <a class="templatemo-white-button ">DEPLOY WORDPRESS</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <ul class="nav nav-tabs">
                        <li ng-class="{
                                    active : flagTab == 'tabProcessDefinition'
                                }">
                            <a  href="#"  ng-click="flagTab = 'tabProcessDefinition'">Process</a>
                        </li>
                        <li ng-class="{
                                    active : flagTab == 'tabDeyloyment'
                                }">
                            <a href="#" ng-click="flagTab = 'tabDeyloyment'">Deployment</a>
                        </li>
                        <li ng-class="{
                                    active : flagTab == 'tabWordpress'
                                }">
                            <a href="#" ng-click="flagTab = 'tabWordpress'" >Wordpress</a>
                        </li>
                    </ul>
                </div>
                <!-- Table Process Group -->
                <div ng-show="flagTab == 'tabProcessDefinition'" class="table-responsive" ng-include="'./resources/views/process.jsp'"></div>
                <!-- Table Deployment Group -->
                <div ng-show="flagTab == 'tabDeyloyment'" class="table-responsive" ng-include="'./resources/views/deployment.jsp'"></div>
                <!-- Table Wordpress Group -->
                <div ng-show="flagTab == 'tabWordpress'" class="table-responsive" ng-include="'./resources/views/wordpress.jsp'"></div>

            </div>
        </div>
    </div>
</div>

<div id="idmodelChooseDeploy" class="modal modelDialog">

    <form class="modal-content animate">
        <div class="imgcontainer">
            <span onclick="document.getElementById('idmodelChooseDeploy').style.display = 'none'" class="close" title="Close Modal">&times;</span>
            <!--<img src="img_avatar2.png" alt="Avatar" class="avatar">-->
            <label class="avatar">Deploy Resources</label>
        </div>

        <div class="container">
            <label for="nameworkspace"><b>Name Workspace</b></label>
            <input type="text" ng-model="workspace.workspaceName" name="nameworkspace" required readonly>
            <label for="psw"><b>Deployment Name</b></label>
            <input type="text" placeholder="Enter Deployment Name" ng-model="deploymentName" id="deploymentName" name="psw" required>
            <label for="tenantid"><b>Tenant Id</b></label>
            <input type="text" placeholder="Enter Tenant Id" ng-model="tenantid" id="tenantid" name="tenantid">
            <input type="file"  onchange='selectFile(event)'>
        </div>

        <div class="container" >
            <button type="button" onclick="document.getElementById('idmodelChooseDeploy').style.display = 'none'" class="cancelbtn">Cancel</button>
            <button type="button" class="sucessbtn" ng-click="AddFileBPMNToWorkspace()">Create</button>
        </div>
    </form>
</div>