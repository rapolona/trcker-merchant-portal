const {DataTypes} = require("sequelize")

module.exports = (sequelize, Sequelize) => {
    const UserSession = sequelize.define("user_session", {
        user_session_id:{
            type: DataTypes.UUID,
            primaryKey: true,
            defaultValue: Sequelize.UUIDV4
        },
        token:{
            type: Sequelize.STRING
        },
    });
    return UserSession
}