const {DataTypes} = require("sequelize")

module.exports = (sequelize, Sequelize) => {
    const AdminSession = sequelize.define("admin_session", {
        admin_session_id:{
            type: DataTypes.UUID,
            primaryKey: true,
            defaultValue: Sequelize.UUIDV4
        },
        token:{
            type: Sequelize.TEXT
        },
    });
    return AdminSession
}