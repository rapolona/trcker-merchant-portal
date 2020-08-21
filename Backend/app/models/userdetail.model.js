const {DataTypes} = require("sequelize")

module.exports = (sequelize, Sequelize) => {
    const UserDetail = sequelize.define("user_detail", {
        user_detail_id:{
            type: DataTypes.UUID,
            primaryKey: true,
            defaultValue: Sequelize.UUIDV4
        },
        first_name:{
            type: Sequelize.STRING
        },
        last_name:{
            type: Sequelize.STRING
        },
        account_level:{
            type: Sequelize.STRING
        },
        email:{
            type: Sequelize.STRING
        },
        user_id:{
            type: DataTypes.UUID,
            allowNull: false,
            references: {        
            model: 'users',
            key: 'user_id'
            }  
        }
    });
    return UserDetail
}