const {DataTypes} = require("sequelize")
module.exports = (sequelize, Sequelize) => {
    const User_payout_request = sequelize.define("user_payout_request", {
        user_payout_request_id:{
            type: DataTypes.UUID,
            primaryKey: true,
            defaultValue: Sequelize.UUIDV4,
            unique:true
        },
        amount:{
            type:Sequelize.FLOAT
        },
        reference_id:{
            type: Sequelize.STRING(255)
        },
        status:{
            type: Sequelize.STRING(64) //can be PENDING or REWARDED
        },
        user_id: {
          type:DataTypes.UUID,
          allowNull:false,
          references: {
            model:"users",
            key:"user_id"
          }
        }
    });

    return User_payout_request
}