const {DataTypes} = require("sequelize")
module.exports = (sequelize, Sequelize) => {
    const Task = sequelize.define("task", {
      task_id:{
        type: DataTypes.UUID,
        primaryKey: true,
        defaultValue: Sequelize.UUIDV4,
        unique:true
      },
      task_name: {
        type: Sequelize.STRING(255),
        allowNull: false
      },
      task_description: {
        type: Sequelize.STRING(255)
      },
      subject_level: {
        type: Sequelize.STRING(64)
      },
      banner_image:{
        type: Sequelize.TEXT('long')
      },
      merchant_id: {
        type: DataTypes.UUID,
        allowNull: true,
        references: {         // Product belongsTo Merchant 1:1
          model: 'merchants',
          key: 'merchant_id'
        }  
      },
      task_classification_id: {
        type: DataTypes.UUID,
        allowNull: true,
        references: {         // Product belongsTo Merchant 1:1
          model: 'task_classifications',
          key: 'task_action_classification_id'
        }  
      }
    });
    return Task;
  };