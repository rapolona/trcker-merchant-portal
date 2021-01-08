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
        birthday:{
            type: Sequelize.DATE
        },
        age:{
            type: Sequelize.INTEGER
        },
        gender:{
            type: Sequelize.STRING(64)
        },
        city:{
            type: Sequelize.STRING(64)
        },
        province:{
            type: Sequelize.STRING(64)
        },
        region:{
            type: Sequelize.STRING(64)
        },
        country:{
            type: Sequelize.STRING(64)
        },
        settlement_account_number:{
            type: Sequelize.STRING(64)
        },
        settlement_account_type:{
            type: Sequelize.STRING(64)
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