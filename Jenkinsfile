pipeline {
    agent any

    environment {
        DOCKER_IMAGE_PHP = 'ow-start:latest'
        DOCKER_IMAGE_MYSQL = 'mysql:latest'
    }

    stages {
        stage('Build PHP Image') {
            steps {
                script {
                    docker.build(DOCKER_IMAGE_PHP, '-f Dockerfile .')
                }
            }
        }

        stage('Test PHP Image') {
            steps {
                script {
                    docker.image(DOCKER_IMAGE_PHP).inside {
                        sh 'php --version'
                    }
                }
            }
        }

        stage('Build and Run Docker Compose') {
            steps {
                script {
                    dockerComposeBuild = "docker-compose -f docker-compose.yml build"
                    dockerComposeUp = "docker-compose -f docker-compose.yml up -d"

                    sh "${dockerComposeBuild}"
                    sh "${dockerComposeUp}"
                }
            }
        }

        stage('Test MySQL Container') {
            steps {
                script {
                    docker.image(DOCKER_IMAGE_MYSQL).inside {
                        sh "mysql --version"
                    }
                }
            }
        }
    }

    post {
        always {
            // Clean up Docker Compose containers
            script {
                sh "docker-compose -f docker-compose.yml down"
            }
        }
    }
}