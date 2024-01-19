pipeline {

    agent any

    stages {

        stage("verify tooling") {
            steps {
            sh '''
                docker version
                docker compose version
                '''
            }
        }

        stage("SonarQube") {

            steps {
                script { scannerHome = tool 'SonarQube Scanner OWS'}
                withSonarQubeEnv('ows_sonarqube') {
                    sh "${scannerHome}/bin/sonar-scanner -Dsonar.projectKey=ows"
                }
            }

        }

        stage("start container") {

            steps {
                sh 'echo "building container"'
                sh 'docker compose up -d --wait'
                sh 'docker compose ps'
            }

        }

        stage("test") {

            steps {
                sh 'echo "testing"'
            }

        }

        stage("deploy") {

            steps {
                sh 'echo "deploying"'
            }

        }
    }
    post {
        always {
            sh 'docker compose down --remove-orphans -v'
            sh 'docker compose ps'
            deleteDir()
        }
    }
}