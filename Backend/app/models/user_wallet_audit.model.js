const {DataTypes} = require("sequelize")
module.exports = (sequelize, Sequelize) => {
    const User_Wallet_Audit = sequelize.define("user_wallet_audit", {
      user_wallet_audit_id:{
        type: DataTypes.UUID,
        primaryKey: true,
        defaultValue: Sequelize.UUIDV4,
        unique:true
      },
      amount_changed_to: {
        type: Sequelize.STRING(255),
        allowNull: false    
      },
      last_updated_by: {
        type: DataTypes.UUID,
      },
      last_updated_by_type: {
        type: Sequelize.STRING(64)
      },
    });
    return User_Wallet_Audit;
  };