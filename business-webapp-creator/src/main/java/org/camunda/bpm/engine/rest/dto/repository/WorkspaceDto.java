/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package org.camunda.bpm.engine.rest.dto.repository;

import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.List;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 *
 * @author TramSunny
 */
public class WorkspaceDto {

    private String workspaceID;
    private String workspaceName;
    private List<ProcessDefinitionDto> listProcess;
    private List<DeploymentDto> listDeployment;
    private List<WordpressDto> listWordpress;

    public List<DeploymentDto> getListDeployment() {
        return listDeployment;
    }

    public void setListDeployment(List<DeploymentDto> listDeployment) {
        this.listDeployment = listDeployment;
    }

    public WorkspaceDto(ResultSet rs) throws SQLException {
        this.workspaceID = rs.getString("workspaceID");
        this.workspaceName = rs.getString("workspaceName");
    }

    public WorkspaceDto(String workspaceID, String workspaceName) {
        this.workspaceID = workspaceID;
        this.workspaceName = workspaceName;
    }

    public String getWorkspaceID() {
        return workspaceID;
    }

    public void setWorkspaceID(String workspaceID) {
        this.workspaceID = workspaceID;
    }

    public String getWorkspaceName() {
        return workspaceName;
    }

    public void setWorkspaceName(String workspaceName) {
        this.workspaceName = workspaceName;
    }

    public List<ProcessDefinitionDto> getListProcess() {
        return listProcess;
    }

    public void setListProcess(List<ProcessDefinitionDto> listProcess) {
        this.listProcess = listProcess;
    }

    public List<WordpressDto> getListWordpress() {
        return listWordpress;
    }

    public void setListWordpress(List<WordpressDto> listWordpress) {
        this.listWordpress = listWordpress;
    }
}
