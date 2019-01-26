<table class="table table-striped table-bordered">
    <thead> 
        <tr>
            <th>Wordpress Name</th>
            <th>Source</th>
            <th>Host</th>
            <th>Database</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <tr ng-repeat="wordpress in workspace.listWordpress">
            <td> {{wordpress.name}}</td>
            <td> {{wordpress.source}}</td>
            <td> {{wordpress.host}}</td>
            <td> {{wordpress.database}}</td>

            <td>
                <div class="btn-toolbar">
                    <div role="group" class="btn-group">
                        <!-- button designs -->
                        <button class="btn btn-default templatemo-blue-button" type="button"
                                ng-click="desginProcess(wordpress.name)">
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
                                ng-click="deleteProcess(wordpress.name)">
                            <i class="glyphicon glyphicon-minus" style="color:red"></i>
                        </button>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>