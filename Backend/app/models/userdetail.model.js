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
        occupation:{
            type: Sequelize.STRING(128)
        },
        industry:{
            type: Sequelize.STRING(128)
        },
        employment_status:{
            type: Sequelize.STRING(64)
        },
        monthly_income_range:{
            type: Sequelize.STRING(64)
        },
        age_range:{
            type: Sequelize.STRING(64)
        },
        residence_type:{
            type: Sequelize.STRING(64)
        },
        LSM_score:{
            type: Sequelize.SMALLINT
        },
        IS_SSS:{
            type: Sequelize.BOOLEAN
        },
        IS_ID_VALIDATED:{
            type: Sequelize.BOOLEAN
        },
        IS_DECISION_MAKER:{
            type: Sequelize.BOOLEAN
        },
        IS_BUYER:{
            type: Sequelize.BOOLEAN
        },
        mobile_phone_number:{
            type: Sequelize.STRING(64)
        },
        user_quality_score:{
            type: Sequelize.STRING(64)
        },
        IS_ACTIVE_P3:{
            type: Sequelize.BOOLEAN
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