/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package org.camunda.bpm.engine.controller;

import com.google.gson.Gson;
import java.util.List;
import javax.ws.rs.Produces;
import javax.ws.rs.core.MediaType;
import org.camunda.bpm.engine.rest.WorkspaceRestService;
import org.camunda.bpm.engine.rest.dto.entity.ActionRespone;
import org.camunda.bpm.engine.rest.dto.entity.ActionResponeError;
import org.camunda.bpm.engine.rest.dto.repository.WorkspaceDto;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestHeader;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.bind.annotation.ResponseBody;

@Controller
@ResponseBody
@Produces(MediaType.APPLICATION_JSON)
public class WorkspaceController {

    @RequestMapping(value = "/engine/default/workspace", method = RequestMethod.GET)
    public List<WorkspaceDto> Workspace() {
        return WorkspaceRestService.getWorkspaces();
    }

    @RequestMapping(value = "/engine/default/workspace/create/", method = RequestMethod.POST)
    public ActionRespone CreateNewWorkspace(@RequestBody String nameWorkspace) {
        return WorkspaceRestService.CreateNewWorkspace(nameWorkspace);
    }

    @RequestMapping(value = "/engine/default/workspace/{workspaceID}/deployment/", method = RequestMethod.POST)
    public ActionRespone DeploymentBPMNToWorkspace(@RequestHeader("Content-Type") String contenttype, @RequestBody String data, @PathVariable("workspaceID") String workspaceID) {
        return WorkspaceRestService.DeploymentBPMNToWorkspace(contenttype, data, workspaceID);
    }

    @RequestMapping(value = "/engine/default/workspace/{workspaceID}/delete/{deploymentID}", method = RequestMethod.DELETE)
    public ActionRespone DeleteDeployment(@PathVariable("workspaceID") String workspaceID, @PathVariable("deploymentID") String deploymentID) {
        return WorkspaceRestService.DeleteDeployment(workspaceID, deploymentID);
    }

}
