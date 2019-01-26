/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package org.camunda.bpm.engine.rest.dto.repository;

import java.sql.ResultSet;
import java.sql.SQLException;

/**
 *
 * @author TramSunny
 */
public class WordpressDto {

    public String id;
    public String name;
    public String source;
    public String host;
    public String database;

    public WordpressDto(ResultSet rs) throws SQLException {
        this.id = rs.getString("id");
        this.name = rs.getString("name");
        this.source = rs.getString("source");
        this.host = rs.getString("host");
        this.database = rs.getString("database");

    }
}
