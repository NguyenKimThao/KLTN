"use strict";

module_home.controller("WordpressController", ["$scope", "$rootScope", "$http", "$interval", "$routeParams",
    function ($scope, $rootScope, $http, $interval, $routeParams) {
        var camClient = new CamSDK.Client({
            mock: false,
            apiUri: '/engine-rest',
            headers: {
                Authorization: "Basic " + $rootScope.globals.currentUser.authdata
            }
        });
        var taskService = new camClient.resource('task');
        var processDefinition = new camClient.resource('process-definition');
        var processInstance = new camClient.resource('process-instance');
        var $formContainer = $('.column.right');

        $scope.workspaceName = $routeParams.workspaceName;
        $scope.wordpressName = $routeParams.wordpressName;
        if (!$scope.wordpressName || !$scope.workspaceName) {
            location.href = "/"
            return;
        }
        var camClient = new CamSDK.Client({
            mock: false,
            apiUri: '/engine-rest',
            headers: {
                Authorization: "Basic " + $rootScope.globals.currentUser.authdata
            }
        });
        var taskService = new camClient.resource('task');



        function  init() {

            $http({
                method: 'GET',
                url: 'engine/default/wordpress/create/' + $scope.wordpressName + '/workspaceName/' + $scope.workspaceName
            }).then(function (response) {
                if (response.data.error)
                    alert('Create no sucess \n Error: ' + response.data.error);
                else
                {
                    $scope.workspace = response.data;
                    LoadListProcess();
                }
            }, function (error) {
                alert('Error in creating' + error);
            });
        }
        var sl = 0;
        function LoadListProcess()
        {
            for (var i = 0; i < $scope.workspace.listProcess.length; i++)
                startProcess($scope.workspace.listProcess[i].id);
        }




        function startProcess(processId) {
            console.log("startProcess" + processId);
            //delete running instance
            if ($scope.processInstanceId) {
                processInstance.delete($scope.processInstanceId, function (err, result) {
                    if (err) {
                        throw err;
                    }

                })
            }
            var date = new Date();
            var dateTime = date.getDate() + "/" + date.getMonth() + 1 + "/" + date.getYear() + 1900 + " " + date.toTimeString().slice("0:8")
            processDefinition.start({id: processId, businessKey: $rootScope.globals.currentUser.username + " " + dateTime}, function (err, results) {
                if (err) {
                    throw err;
                }
                $scope.processInstanceId = results.id;
                loadTasks(results.id);
            });
        }
        function loadTasks(processInstanceId) {
            $scope.processInstanceId = processInstanceId
            $scope.tableView = false;
            taskService.list({processInstanceId: processInstanceId}, function (err, results) {
                if (err) {
                    throw err;
                }
                if (results.count == 0) {
                } else {
                    $scope.$apply(function () {
                        loadTaskForm(results._embedded.task[0]);
                        $scope.taskName = results._embedded.task[0].name;
                    });
                }
            });
        }


        function addFormButton(err, camForm) {
            if (err) {
                throw err;
            }


//            console.log('okaddFormButton');
//            console.log(camForm);
            // create a button element
//            var formName = $('form').attr('name');
//            var $submitBtn = $('<button type="button" ng-disabled="' + formName + '.$invalid" class="templatemo-blue-button pull-right">Complete</button>')
//                    // with a click handler to submit the form
//                    .click(function () {
//                        camForm.submit(function (err) {
//                            if (err) {
//                                throw err;
//                            }
//                            // clear the form
//                            $formContainer.html('');
//                            $scope.taskName = null;
//                            loadTasks($scope.processInstanceId)
//                        });
//                    });
//            camForm.on('submit-success', function (err) {
//                $formContainer.html('');
//                $scope.taskName = null;
//                loadTasks($scope.processInstanceId);
//            });
//            // and append it to the form
//            camForm.formElement.append($submitBtn);
        }

        function doneLoadForm(err, camForm) {
            console.log(camForm);



//            $http({
//                method: 'POST',
//                url: 'engine/default/workspace/aciton/addForm/',
//                data: data
//            }).then(function (response) {
//                if (!response.data.error)
//                {
//                    console.log(response.data);
//                }
//            });
        }


        function loadTaskForm(task) {

            // clear the form container content
            $formContainer.html('');
            // loads the task form using the task ID provided
            taskService.form(task.id, function (err, taskFormInfo) {
                var key = taskFormInfo.key;
                var url;
                var doneFunction;

                if (key.includes('embedded:engine://engine/:engine')) {

                    url = key.replace('embedded:engine://engine/:engine', window.location.origin + '/engine-rest');
                } else {
                    url = key.replace('embedded:app:', window.location.origin + "/" + taskFormInfo.contextPath + "/");
                }

                $http({
                    method: 'GET',
                    url: url,
                }).then(function (response) {

                    SendData(task, response.data);
                });
            });
        }
        function  SendData(task, content)
        {
            var pro = task._embedded.processDefinition[0];
            var data = {};
            data.workspaceName = $scope.workspaceName;
            data.wordpressName = $scope.wordpressName;
            data.post_content = content;
            data.post_tile = pro.name;
            data.post_name = 'process_' + pro.key;


            $http({
                method: 'POST',
                url: 'engine/default/workspace/aciton/addForm/',
                data: data
            }).then(function (response) {
                sl = sl + 1;
                if ($scope.workspace.listProcess.length === sl)
                    location.href = "http://localhost:8888/" + $scope.wordpressName + "/wp-admin/plugins.php";
                if (!response.data.error)
                {
                    console.log(response.data);
                }
            });
        }

        init();


    }]);
