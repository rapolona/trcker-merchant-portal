const {DataTypes} = require("sequelize")
module.exports = (sequelize, Sequelize) => {
    const Task_Action = sequelize.define("task_action", {
      task_action_id:{
        type: DataTypes.UUID,
        primaryKey: true,
        defaultValue: Sequelize.UUIDV4,
        unique:true
      },
      task_action_name: {
        type: Sequelize.STRING,
        unique:true,
        allowNull: false
      },
      task_action_description: {
        type: Sequelize.STRING
      },
      subject_level: {
        type: Sequelize.STRING
      },
      merchant_id: {
        type: DataTypes.UUID,
        allowNull: true,
        references: {         // Product belongsTo Merchant 1:1
          model: 'merchants',
          key: 'merchant_id'
        }  
      },
      task_action_classification_id: {
        type: DataTypes.UUID,
        allowNull: false,
        references: {         // Product belongsTo Merchant 1:1
          model: 'task_action_classifications',
          key: 'task_action_classification_id'
        }  
      }
    });
    return Task_Action;
  };