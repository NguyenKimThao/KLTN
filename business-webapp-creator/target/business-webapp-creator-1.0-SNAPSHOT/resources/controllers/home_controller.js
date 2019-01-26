"use strict";
module_home.controller("HomeController", ["$scope", "$rootScope", "$interval", "$http", function ($scope, $rootScope, $interval, $http) {


        $scope.tableViewGroupProcess = false;
        $scope.prevProcessTab = false;
        $scope.flagTab = "tabProcessDefinition";
        $scope.workspace = null;
        var modal = document.getElementById('idmodel');

        function  LoadWorkSpaces()
        {
            var urlWorkspaces = "./engine/default/workspace/";
            $http.get(urlWorkspaces).then(function (response) {
                $scope.workspaces = response.data;
                if ($scope.workspace != null)
                {
                    $scope.workspace = $scope.workspaces.filter(workspace => workspace.workspaceID == $scope.workspace.workspaceID)[0];
                }
                console.log($scope.workspaces);
            });
        }



        function CreateNewWorkspace() {
            $http({
                method: 'POST',
                url: 'engine/default/workspace/create/',
                data: $scope.nameworkspace
            }).then(function (respone) {
                var data = respone.data;
                console.log(data);
                if (data.type == 'error')
                    alert('Create no sucess \n Error: ' + data.message);
                else
                {
                    modal.style.display = "none";
                    LoadWorkSpaces();
                }
            }
            , function (error) {
                alert('Error in creating' + error);
            });
        }

        window.selectFile = function (event) {
            $scope.selectFile = event.target.files[0];
        }


        function CreateNewWorkspaceByWar() {

            var fd = new FormData();
            fd.append('deployment-name', 'aName');
            fd.append('enable-duplicate-filtering', false);
            fd.append('data', $scope.selectFile);
            $http({
                method: 'POST',
                url: '/engine-rest/engine/default/deployment/create',
                data: fd,
                transformRequest: angular.identity,
                headers: {'Content-Type': undefined}
            }).then(function (respone) {
                SendDeploymenResoucre(respone.data.id);
            }
            , function (error) {
                alert('Error in creating' + error);
            });
        }
        $scope.AddFileBPMNToWorkspace = function () {

            var fd = new FormData();
            fd.append('deployment-name', $("#deploymentName").val());
            fd.append('tenant-id', $("#tenantid").val());
            fd.append('deployment-source', 'process application');
            fd.append('enable-duplicate-filtering', true);
            fd.append('data', $scope.selectFile);
            $http({
                method: 'POST',
                url: 'engine/default/workspace/' + $scope.workspace.workspaceID + '/deployment/',
                data: fd,
                transformRequest: angular.identity,
                headers: {'Content-Type': undefined}
            }).then(function (respone) {
                console.log(respone.data);
                if (respone.data.type = 'sucess')
                {
                    document.getElementById('idmodelChooseDeploy').style.display = 'none';
                    LoadWorkSpaces();
                }
                alert(respone.data.message);

            }, function (error) {
                alert('Error in creating' + error);
            });
        }

        //start a process
        $scope.startWorkspace = function (workspace) {
            $scope.tableViewGroupProcess = true;
            $scope.workspace = workspace;
        }

        $scope.ActionWorkspace = function () {
            if ($scope.deployfile == false) {
                CreateNewWorkspace();
            } else
            {
                CreateNewWorkspaceByWar();
            }
        }
        $scope.showCreateNewWorkspace = function () {
            $scope.deployfile = false;
            document.getElementById('idmodel').style.display = 'block';
        }

        $scope.showDeployWarWorkspace = function () {
            $scope.deployfile = true;
            document.getElementById('idmodel').style.display = 'block';
        }
        $scope.chooseDeployBPMN = function () {
            document.getElementById('idmodelChooseDeploy').style.display = 'block';
        }
        $scope.chooseDeployWordpress = function () {
            var ngay = new Date();
            window.open("http://localhost:8888/wp-quick-install/index.php?workspaceName=" + $scope.workspace.workspaceName, "_blank");
        }


        LoadWorkSpaces();
        window.onclick = function (event) {
            if (event.target == modal) {
                document.getElementById('idmodelChooseDeploy').style.display = 'none';
                modal.style.display = "none";
                $scope.deployfile = false;
            }
        }












        ///////////////////////////////Nhung thu can///////////////////////////


        $scope.deleteDeyloyment = function (deploymentId) {
            $http({
                method: 'DELETE',
                url: 'engine/default/workspace/' + $scope.workspace.workspaceID + '/delete/' + deploymentId,
                headers: {'Content-Type': undefined}
            }).then(function (respone) {
                console.log(respone.data);
                if (respone.data.type == 'sucess')
                    LoadWorkSpaces();
                alert(respone.data.message);
            }, function (error) {
                alert('Error in creating' + error);
            });
        }



    }]);
