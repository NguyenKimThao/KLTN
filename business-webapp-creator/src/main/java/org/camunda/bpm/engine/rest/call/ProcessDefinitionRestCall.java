/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package org.camunda.bpm.engine.rest.call;

import com.google.gson.Gson;
import java.io.IOException;
import java.util.Arrays;
import java.util.List;
import org.apache.http.HttpResponse;
import org.apache.http.client.ResponseHandler;
import org.apache.http.impl.client.BasicResponseHandler;
import org.camunda.bpm.engine.rest.RestEnginerCall;
import org.camunda.bpm.engine.rest.RestEnginerCurrentService;
import org.camunda.bpm.engine.rest.dto.repository.DeploymentResourceDto;
import org.camunda.bpm.engine.rest.dto.repository.ProcessDefinitionDto;

/**
 *
 * @author TramSunny
 */
public class ProcessDefinitionRestCall {

    public static ProcessDefinitionDto getProcessDefinitionByID(String ID) throws IOException {
        ProcessDefinitionDto process = null;
        RestEnginerCall res = RestEnginerCurrentService.getRestEngineCurrent();
        if (res == null) {
            return null;
        }

        try {
            String body = res.execute("/engine-rest/process-definition/" + ID);
            if (body == null) {
                return null;
            }
            Gson json = new Gson();
            process = json.fromJson(body, ProcessDefinitionDto.class);
        } catch (Exception ex) {
        }
        return process;
    }

    public static List<ProcessDefinitionDto> GetProcessDefinitionByDeploymentRescource(String deploymentId) {
        RestEnginerCall res = RestEnginerCurrentService.getRestEngineCurrent();
        String body = res.execute("/engine-rest/process-definition?deploymentId=" + deploymentId);
        Gson json = new Gson();
        ProcessDefinitionDto[] listProces = json.fromJson(body, ProcessDefinitionDto[].class);
        return Arrays.asList(listProces);

    }
}
