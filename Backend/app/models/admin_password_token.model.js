const {DataTypes} = require("sequelize")

module.exports = (sequelize, Sequelize) => {
    const AdminPasswordToken = sequelize.define("admin_password_token", {
        password_token_id:{
            type: DataTypes.UUID,
            primaryKey: true,
            defaultValue: Sequelize.UUIDV4
        },
        token:{
            type: Sequelize.STRING
        },
        admin_id:{
            type: DataTypes.UUID,
            allowNull: false,
            references: {        
            model: 'admins',
            key: 'admin_id'
            }  
        },
        status:{
            type:Sequelize.BOOLEAN
        }
    });
    return AdminPasswordToken
}