/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package org.camunda.bpm.engine.rest;

import java.io.IOException;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;
import java.util.logging.Level;
import java.util.logging.Logger;
import org.camunda.bpm.engine.db.CamundaConnection;
import org.camunda.bpm.engine.db.WordpressDAO;
import org.camunda.bpm.engine.db.WorkspaceDAO;
import org.camunda.bpm.engine.rest.call.DeploymentRestCall;
import org.camunda.bpm.engine.rest.call.ProcessDefinitionRestCall;
import org.camunda.bpm.engine.rest.dto.entity.ActionRespone;
import org.camunda.bpm.engine.rest.dto.entity.ActionResponeError;
import org.camunda.bpm.engine.rest.dto.entity.ActionResponeSucess;
import org.camunda.bpm.engine.rest.dto.repository.DeploymentDto;
import org.camunda.bpm.engine.rest.dto.repository.DeploymentResourceDto;
import org.camunda.bpm.engine.rest.dto.repository.DeploymentWithDefinitionsDto;
import org.camunda.bpm.engine.rest.dto.repository.ProcessDefinitionDto;
import org.camunda.bpm.engine.rest.dto.repository.WordpressDto;
import org.camunda.bpm.engine.rest.dto.repository.WorkspaceDto;
import org.springframework.security.authentication.dao.DaoAuthenticationProvider;

/**
 *
 * @author TramSunny
 */
public class WorkspaceRestService {

    public static List<WorkspaceDto> getWorkspaces() {
        try {
            List<WorkspaceDto> list = WorkspaceDAO.getWorkspaces();
            for (WorkspaceDto workspaceDto : list) {
                List<String> listprocesIDs = WorkspaceDAO.getProcessByWordspaceID(workspaceDto.getWorkspaceID());
                List<String> listDeploymentID = WorkspaceDAO.getDeploymentByWordspaceID(workspaceDto.getWorkspaceID());
                List<String> listWordpressID = WorkspaceDAO.getWordpressByWorkspaceID(workspaceDto.getWorkspaceID());
                List<ProcessDefinitionDto> listProcessDefinitions = new ArrayList<ProcessDefinitionDto>();
                List<DeploymentDto> listDeploymentDtos = new ArrayList<DeploymentDto>();
                List<WordpressDto> listWordpress = new ArrayList<WordpressDto>();
                for (String listprocesID : listprocesIDs) {
                    ProcessDefinitionDto process = ProcessDefinitionRestCall.getProcessDefinitionByID(listprocesID);
                    if (process != null) {
                        listProcessDefinitions.add(process);
                    }
                }
                for (String deploymentID : listDeploymentID) {
                    DeploymentDto deployment = DeploymentRestCall.getDeploymentByID(deploymentID);
                    if (deployment != null) {
                        listDeploymentDtos.add(deployment);
                    }
                }
                for (String wordpressID : listWordpressID) {
                    WordpressDto wordpress = WordpressDAO.getWordpressByWordpressID(wordpressID);
                    if (wordpress != null) {
                        listWordpress.add(wordpress);
                    }
                }

                workspaceDto.setListProcess(listProcessDefinitions);
                workspaceDto.setListDeployment(listDeploymentDtos);
                workspaceDto.setListWordpress(listWordpress);

            }
            return list;
        } catch (SQLException ex) {
            Logger.getLogger(WorkspaceRestService.class.getName()).log(Level.SEVERE, null, ex);
        } catch (IOException ex) {
            Logger.getLogger(WorkspaceRestService.class.getName()).log(Level.SEVERE, null, ex);
        }
        System.out.println("Voaday");
        return null;
    }

    public static ActionRespone CreateNewWorkspace(String nameWorkspace) {
        String error = "No create sucess :";
        try {
            return WorkspaceDAO.CreateNewWorkspace(nameWorkspace);
        } catch (SQLException ex) {
            error += ex.getMessage();
            Logger.getLogger(WorkspaceRestService.class.getName()).log(Level.SEVERE, null, ex);
        }
        return new ActionResponeError("CreateNewWorkspace", error);
    }

    public static ActionRespone DeploymentBPMNToWorkspace(String type, String data, String workspaceID) {
        String error = "No deploy sucess :";
        try {

            DeploymentDto deployment = DeploymentRestCall.DeploymentBPMNToWorkspace(type, data);
            if (deployment == null) {
                return new ActionResponeError("DeploymentBPMNToWorkspace", "deployment error");
            }
            System.out.println(deployment);
            ActionRespone actionres = WorkspaceDAO.AddDeploymentToWorkspace(workspaceID, deployment.getId());
            if (actionres instanceof ActionResponeError) {
                return actionres;
            }
            List<ProcessDefinitionDto> listProcessDefinition = ProcessDefinitionRestCall.GetProcessDefinitionByDeploymentRescource(deployment.getId());
            for (ProcessDefinitionDto deployedProcessDefinition : listProcessDefinition) {
                System.out.println(deployedProcessDefinition.getId());
                actionres = WorkspaceDAO.AddProcessToWorkspace(workspaceID, deployedProcessDefinition.getId());
                if (actionres instanceof ActionResponeError) {
                    return actionres;
                }
            }

            return new ActionResponeSucess("CreateNewWorkspace", "deployment sucess");
        } catch (Exception ex) {
            error += ex.getMessage();
            Logger.getLogger(WorkspaceRestService.class.getName()).log(Level.SEVERE, null, ex);
        }
        return new ActionResponeError("CreateNewWorkspace", error);
    }

    public static ActionRespone DeleteDeployment(String workspaceID, String deploymentID) {
        try {
            List<ProcessDefinitionDto> listProcessDefinition = ProcessDefinitionRestCall.GetProcessDefinitionByDeploymentRescource(deploymentID);
            for (ProcessDefinitionDto processDefinitionDto : listProcessDefinition) {
                ActionRespone actionres = WorkspaceDAO.DeleteProcessByWorkspaceID(workspaceID, processDefinitionDto);
                if (actionres instanceof ActionResponeError) {
                    return actionres;
                }
            }

            ActionRespone actionres = WorkspaceDAO.DeleteDeploymentByWorkspaceID(workspaceID, deploymentID);
            if (actionres instanceof ActionResponeError) {
                return actionres;
            }
            return DeploymentRestCall.DeleteDeployment(deploymentID);

        } catch (SQLException ex) {
            Logger.getLogger(WorkspaceRestService.class.getName()).log(Level.SEVERE, null, ex);
        }
        return new ActionResponeError("DeleteDeployment", "DeleteDeployment error");
    }
}
