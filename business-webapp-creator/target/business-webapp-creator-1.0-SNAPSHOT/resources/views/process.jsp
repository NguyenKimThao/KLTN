<table class="table table-striped table-bordered">
    <thead> 
        <tr>
            <th>Process Name</th>
            <th>Process Key</th>
            <th>Resource</th>
            <th>Version</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <tr ng-repeat="process in workspace.listProcess">
            <td> {{process.name}}</td>
            <td> {{process.key}} </td>
            <td> {{process.resource}}</td>
            <td> {{process.version}}</td>
            <td>
                <div class="btn-toolbar">
                    <div role="group" class="btn-group">
                        <!-- button designs -->
                        <button class="btn btn-default templatemo-blue-button" type="button"
                                ng-click="desginProcess(process.id)">
                            <i class="glyphicon glyphicon-pencil"></i>
                        </button>
                    </div>
                    <div role="group" class="btn-group">
                        <!-- button review -->
                        <button class="btn btn-default templatemo-blue-button" type="button">
                            <i class="glyphicon glyphicon-film"></i></button>
                        </button>
                    </div>
                    <div class="btn-group" role="group">
                        <!-- button delete -->
                        <button class="btn btn-default templatemo-blue-button" type="button"
                                ng-click="deleteProcess(process.deploymentId)">
                            <i class="glyphicon glyphicon-minus" style="color:red"></i>
                        </button>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>