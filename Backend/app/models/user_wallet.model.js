const {DataTypes} = require("sequelize")
module.exports = (sequelize, Sequelize) => {
    const User_wallet = sequelize.define("user_wallet", {
        user_wallet_id:{
            type: DataTypes.UUID,
            primaryKey: true,
            defaultValue: Sequelize.UUIDV4,
            unique:true
        },
        current_amount:{
            type:Sequelize.FLOAT,
            defaultValue: 0
        },
        user_id: {
          type:DataTypes.UUID,
          allowNull:false,
          unique:true,
          references: {
            model:"users",
            key:"user_id"
          }
        }
    });

    return User_wallet
}