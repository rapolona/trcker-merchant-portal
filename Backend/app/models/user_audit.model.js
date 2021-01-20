const {DataTypes} = require("sequelize")
module.exports = (sequelize, Sequelize) => {
    const User_Audit = sequelize.define("user_audit", {
      user_audit_id:{
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
      reason:{
        type: Sequelize.TEXT
      },
      user_id:{
        type: DataTypes.UUID,
        allowNull: false,
        references: {         // Product belongsTo Merchant 1:1
          model: 'users',
          key: 'user_id'
        }  
      }
    });
    return User_Audit;
  };