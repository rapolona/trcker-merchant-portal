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
        },
        cityId:{
            type: DataTypes.INTEGER,
            allowNull: true,
            references: {
                model:'city',
                key: 'Id'
            }
        },
        provinceId:{
            type: DataTypes.INTEGER,
            allowNull: true,
            references: {
                model:'province',
                key: 'Id'
            }
        },
        regionId:{
            type: DataTypes.INTEGER,
            allowNull: true,
            references: {
                model:'region',
                key: 'Id'
            }
        },
    });
    return UserDetail
}