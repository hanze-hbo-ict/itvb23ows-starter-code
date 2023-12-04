pipeline{
    agent any
    stages{
        stage('Start container'){
            steps{
                sh 'docker compose up --build'
                sh 'docker compose ps'
            }
        }
    }
}