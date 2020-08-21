const {DataTypes} = require("sequelize")
module.exports = (sequelize, Sequelize) => {
    const User = sequelize.define("user", {
        user_id:{
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
        status:{
            type: Sequelize.STRING
        }
    });

    return User
}