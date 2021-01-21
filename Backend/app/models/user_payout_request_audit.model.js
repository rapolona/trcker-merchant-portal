const {DataTypes} = require("sequelize")
module.exports = (sequelize, Sequelize) => {
    const User_Payout_Requests_Audit = sequelize.define("user_payout_requests_audit", {
      user_payout_request_audit_id:{
        type: DataTypes.UUID,
        primaryKey: true,
        defaultValue: Sequelize.UUIDV4,
        unique:true
      },
      status_changed_to: {
        type: Sequelize.STRING(255),
        allowNull: false    
      },
      last_updated_by: {
        type: DataTypes.UUID,
      },
      last_updated_by_type: {
        type: Sequelize.STRING(64)
      },
      user_payout_request_id:{
        type: DataTypes.UUID,
        allowNull: false,
        references: {         // Product belongsTo Merchant 1:1
          model: 'user_payout_requests',
          key: 'user_payout_request_id'
        }  
      }
    });
    return User_Payout_Requests_Audit;
  };