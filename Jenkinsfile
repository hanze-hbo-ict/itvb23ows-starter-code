pipeline {
    agent { docker { image 'php:8.3.0-alpine3.18' } }
    stages {
        stage('Build') {
            steps {
                sh 'php --version'
            }
        }
        stage('SonarQube Analysis') {
            steps {
                script {
                    docker.image('sonarsource/sonar-scanner-cli').inside {
                        sh 'sonar-scanner -Dsonar.projectKey=ows -Dsonar.host.url=http://sonarqube:9000/'
                    }
                }
            }
        }
    }
}
