const {DataTypes} = require("sequelize")
module.exports = (sequelize, Sequelize) => {
    const Task_Question_Choices = sequelize.define("task_question_choices", {
      choices_id:{
        type: DataTypes.UUID,
        primaryKey: true,
        defaultValue: Sequelize.UUIDV4,
        unique:true
      },
      choices_value: {
        type: Sequelize.STRING,
        allowNull: false,
      },
      next_label: {
        type: Sequelize.STRING(64),
        allowNull: true
      },
      task_question_id: {
        type: DataTypes.UUID,
        allowNull: false,
        references: {         // User belongsTo Company 1:1
          model: 'task_questions',
          key: 'task_question_id'
        }  
      },
    });
    return Task_Question_Choices;
  };