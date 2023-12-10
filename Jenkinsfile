pipeline{
    agent {
        docker {image 'php:8.3-apache'}
    }

    stages{
        stage('Test PHP Build'){
            steps{
                sh 'php --version'
            }
        }
        stage('SonarQube scan'){
            steps{
                sh 'The SonarQube scan stage will be created here'
            }
        }
    }
}