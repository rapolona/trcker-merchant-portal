const {DataTypes} = require("sequelize")
module.exports = (sequelize, Sequelize) => {
    const Admin = sequelize.define("admin", {
        admin_id:{
            type: DataTypes.UUID,
            primaryKey: true,
            defaultValue: Sequelize.UUIDV4,
            unique:true
        },
        username:{
            type:Sequelize.STRING,
            allowNull: false,
            unique: true
        },
        password:{
            type: Sequelize.STRING
        },
        password_salt:{
            type: Sequelize.STRING
        },
    });

    return Admin
}