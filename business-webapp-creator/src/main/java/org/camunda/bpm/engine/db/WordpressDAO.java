/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package org.camunda.bpm.engine.db;

import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;
import org.camunda.bpm.engine.rest.dto.repository.WordpressDto;

/**
 *
 * @author TramSunny
 */
public class WordpressDAO {

    public static WordpressDto getWordpressByWordpressID(String wordpressID) throws SQLException {
        String query = "select *from wordpress  where id= ? ";
        ResultSet res = CamundaConnection.executeQuery(query, wordpressID);
        return new WordpressDto(res);
    }
}
