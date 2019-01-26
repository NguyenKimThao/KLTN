/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package org.camunda.bpm.engine.db;

import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;
import java.util.logging.Level;
import java.util.logging.Logger;
import org.camunda.bpm.engine.rest.WorkspaceRestService;
import org.camunda.bpm.engine.rest.dto.entity.ActionRespone;
import org.camunda.bpm.engine.rest.dto.entity.ActionResponeError;
import org.camunda.bpm.engine.rest.dto.entity.ActionResponeSucess;
import org.camunda.bpm.engine.rest.dto.repository.ProcessDefinitionDto;
import org.camunda.bpm.engine.rest.dto.repository.WorkspaceDto;

/**
 *
 * @author TramSunny
 */
public class WorkspaceDAO {

    public static List<WorkspaceDto> getWorkspaces() throws SQLException {
        List<WorkspaceDto> list = new ArrayList<>();
        String query = "select *from workspace ";
        ResultSet res = CamundaConnection.executeQuery(query, true);
        while (res.next()) {
            list.add(new WorkspaceDto(res));
        }
        return list;

    }

    public static List<String> getProcessByWordspaceID(String workspaceID) throws SQLException {
        List<String> list = new ArrayList<>();
        String query = "select *from workspace_process  where workspaceID= ? ";
        ResultSet res = CamundaConnection.executeQuery(query, workspaceID, true);
        while (res.next()) {
            list.add(res.getString("processID"));
        }
        return list;
    }

    public static List<String> getWordpressByWorkspaceID(String workspaceID) throws SQLException {
        List<String> list = new ArrayList<>();
        String query = "select *from workspace_wordpress  where workspaceID= ? ";
        ResultSet res = CamundaConnection.executeQuery(query, workspaceID, true);
        while (res.next()) {
            list.add(res.getString("wordpress"));
        }
        return list;
    }

    public static List<String> getDeploymentByWordspaceID(String workspaceID) throws SQLException {
        List<String> list = new ArrayList<>();
        String query = "select *from workspace_deployment  where workspaceID= ? ";
        ResultSet res = CamundaConnection.executeQuery(query, workspaceID, true);
        while (res.next()) {
            list.add(res.getString("deploymentID"));
        }
        return list;
    }

    public static boolean CheckWorkspaceByname(String nameWorkspace) throws SQLException {
        String query = "select * from workspace where workspaceName=?";
        ResultSet res = CamundaConnection.executeQuery(query, nameWorkspace, true);
        if (!res.next()) {
            return false;
        }
        WorkspaceDto workspace = new WorkspaceDto(res);
        if (workspace == null) {
            return false;
        }
        return true;
    }

    public static boolean CheckWorkspaceByID(String workspaceID) throws SQLException {
        String query = "select * from workspace where workspaceID=?";
        ResultSet res = CamundaConnection.executeQuery(query, workspaceID, true);
        if (!res.next()) {
            return false;
        }
        WorkspaceDto workspace = new WorkspaceDto(res);
        if (workspace == null) {
            return false;
        }
        return true;
    }

    public static ActionRespone CreateNewWorkspace(String nameWorkspace) throws SQLException {
        nameWorkspace = nameWorkspace.toUpperCase();
        if (CheckWorkspaceByname(nameWorkspace)) {
            return new ActionResponeError("CreateNewWorkspace", nameWorkspace + " is exists");
        }
        Date date = new Date();
        String idWorkspace = "Workspace-" + date.getTime();
        System.out.println("idWorkspace!!!!" + idWorkspace);
        String query = "insert into workspace(workspaceID,workspaceName) value(?,?)";
        List<Object> list = new ArrayList<Object>();
        list.add(idWorkspace);
        list.add(nameWorkspace);
        int res = CamundaConnection.executeUpdate(query, list);
        if (res != 1) {
            return new ActionResponeError("CreateNewWorkspace", "No create sucess");
        }
        return new ActionResponeSucess("CreateNewWorkspace", "Create sucess");
    }

    public static ActionRespone AddProcessToWorkspace(String workspaceID, String processID) throws SQLException {

        if (!CheckWorkspaceByID(workspaceID)) {
            return new ActionResponeError("AddProcessToWorkspace", workspaceID + " isn't exists");
        }
        String query = "insert into workspace_process(workspaceID,processID) values(?,?)";
        List<Object> object = new ArrayList<>();
        object.add(workspaceID);
        object.add(processID);
        int res = CamundaConnection.executeUpdate(query, object);
        if (res != 1) {
            return new ActionResponeError("AddProcessToWorkspace", "No create sucess when add database");
        }
        return new ActionResponeSucess("AddProcessToWorkspace", "Create sucess");
    }

    public static ActionRespone AddDeploymentToWorkspace(String workspaceID, String deploymentID) throws SQLException {
        if (!CheckWorkspaceByID(workspaceID)) {
            return new ActionResponeError("AddDeploymentToWorkspace", workspaceID + " isn't exists");
        }
        String query = "insert into workspace_deployment(workspaceID,deploymentID) values(?,?)";
        List<Object> object = new ArrayList<>();
        object.add(workspaceID);
        object.add(deploymentID);
        int res = CamundaConnection.executeUpdate(query, object);
        if (res != 1) {
            return new ActionResponeError("AddDeploymentToWorkspace", "No create sucess when add database");
        }
        return new ActionResponeSucess("AddProcessToWorkspace", "Create sucess");

    }

    public static ActionRespone DeleteDeploymentByWorkspaceID(String workspaceID, String deploymentID) throws SQLException {
        if (!CheckWorkspaceByID(workspaceID)) {
            return new ActionResponeError("DeleteDeploymentByWorkspaceID", workspaceID + " isn't exists");
        } else {

            String query = "delete from  workspace_deployment where workspaceID = ? AND deploymentID = ? ";
            List<Object> object = new ArrayList<>();
            object.add(workspaceID);
            object.add(deploymentID);
            int res = CamundaConnection.executeUpdate(query, object);
            if (res != 1) {
                return new ActionResponeError("DeleteDeploymentByWorkspaceID", "No create sucess when delete in  database");
            }
        }
        return new ActionResponeSucess("DeleteDeploymentByWorkspaceID", "Create sucess");

    }

    public static ActionRespone DeleteProcessByWorkspaceID(String workspaceID, ProcessDefinitionDto processDefinition) throws SQLException {
        if (!CheckWorkspaceByID(workspaceID)) {
            return new ActionResponeError("DeleteProcessByWorkspaceID", workspaceID + " isn't exists");
        } else {
            String query = "delete from  workspace_process where workspaceID = ? AND processID = ? ";
            List<Object> object = new ArrayList<>();
            object.add(workspaceID);
            object.add(processDefinition.getId());
            int res = CamundaConnection.executeUpdate(query, object);
            if (res != 1) {
                return new ActionResponeError("DeleteProcessByWorkspaceID", "No create sucess when delete in  database");
            }
        }
        return new ActionResponeSucess("DeleteProcessByWorkspaceID", "Create sucess");
    }

}
