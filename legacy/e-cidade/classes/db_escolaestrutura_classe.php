<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: escola
//CLASSE DA ENTIDADE escolaestrutura
class cl_escolaestrutura { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $ed255_i_codigo = 0; 
   var $ed255_i_escola = 0; 
   var $ed255_i_compartilhado = 0; 
   var $ed255_i_escolacompartilhada = 0; 
   var $ed255_i_salaexistente = 0; 
   var $ed255_i_salautilizada = 0; 
   var $ed255_c_abastagua = null; 
   var $ed255_c_abastenergia = null; 
   var $ed255_i_aguafiltrada = 0; 
   var $ed255_c_esgotosanitario = null; 
   var $ed255_c_destinolixo = null; 
   var $ed255_c_localizacao = null; 
   var $ed255_c_dependencias = null; 
   var $ed255_c_equipamentos = null; 
   var $ed255_i_computadores = 0; 
   var $ed255_i_qtdcomp = 0; 
   var $ed255_i_qtdcompadm = 0; 
   var $ed255_i_qtdcompalu = 0; 
   var $ed255_i_internet = 0; 
   var $ed255_i_bandalarga = 0; 
   var $ed255_i_alimentacao = 0; 
   var $ed255_i_ativcomplementar = 0; 
   var $ed255_c_materdidatico = null; 
   var $ed255_i_aee = 0; 
   var $ed255_i_efciclos = 0; 
   var $ed255_i_formaocupacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed255_i_codigo = int8 = Código 
                 ed255_i_escola = int4 = Código da Escola 
                 ed255_i_compartilhado = int4 = Predio compartilhado 
                 ed255_i_escolacompartilhada = int4 = Código INEP da Outra Escola 
                 ed255_i_salaexistente = int4 = N° de Sala de Aula Existentes na Escola 
                 ed255_i_salautilizada = int4 = N° de Salas Utilizadas como Sala de Aula 
                 ed255_c_abastagua = char(10) = Abastecimento de Água 
                 ed255_c_abastenergia = char(10) = Abastecimento de Energia 
                 ed255_i_aguafiltrada = int4 = Água Consumida pelos Alunos 
                 ed255_c_esgotosanitario = char(10) = Esgoto Sanitario 
                 ed255_c_destinolixo = char(10) = Destinação do Lixo 
                 ed255_c_localizacao = char(10) = Local de Funcionamento 
                 ed255_c_dependencias = char(20) = Dependências Existentes 
                 ed255_c_equipamentos = char(20) = Equipamentos Existentes 
                 ed255_i_computadores = int4 = Computadores 
                 ed255_i_qtdcomp = int4 = Qtde. de Computadores na Escola 
                 ed255_i_qtdcompadm = int4 = Qtde. de Computadores Uso Administrativo 
                 ed255_i_qtdcompalu = int4 = Qtde. de Computadores Uso de Alunos 
                 ed255_i_internet = int4 = Acesso à Internet 
                 ed255_i_bandalarga = int4 = Banda Larga 
                 ed255_i_alimentacao = int4 = Alimentação Escolar para os Alunos 
                 ed255_i_ativcomplementar = int4 = Atividade Complementar 
                 ed255_c_materdidatico = char(10) = Materais Didáticos Específicos 
                 ed255_i_aee = int4 = Atendimento Educ. Especializado AEE 
                 ed255_i_efciclos = int4 = Ensino Fundamental em ciclos 
                 ed255_i_formaocupacao = int4 = Forma de Ocupação do Prédio 
                 ";
   //funcao construtor da classe 
   function cl_escolaestrutura() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("escolaestrutura"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->ed255_i_codigo = ($this->ed255_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed255_i_codigo"]:$this->ed255_i_codigo);
       $this->ed255_i_escola = ($this->ed255_i_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed255_i_escola"]:$this->ed255_i_escola);
       $this->ed255_i_compartilhado = ($this->ed255_i_compartilhado == ""?@$GLOBALS["HTTP_POST_VARS"]["ed255_i_compartilhado"]:$this->ed255_i_compartilhado);
       $this->ed255_i_escolacompartilhada = ($this->ed255_i_escolacompartilhada == ""?@$GLOBALS["HTTP_POST_VARS"]["ed255_i_escolacompartilhada"]:$this->ed255_i_escolacompartilhada);
       $this->ed255_i_salaexistente = ($this->ed255_i_salaexistente == ""?@$GLOBALS["HTTP_POST_VARS"]["ed255_i_salaexistente"]:$this->ed255_i_salaexistente);
       $this->ed255_i_salautilizada = ($this->ed255_i_salautilizada == ""?@$GLOBALS["HTTP_POST_VARS"]["ed255_i_salautilizada"]:$this->ed255_i_salautilizada);
       $this->ed255_c_abastagua = ($this->ed255_c_abastagua == ""?@$GLOBALS["HTTP_POST_VARS"]["ed255_c_abastagua"]:$this->ed255_c_abastagua);
       $this->ed255_c_abastenergia = ($this->ed255_c_abastenergia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed255_c_abastenergia"]:$this->ed255_c_abastenergia);
       $this->ed255_i_aguafiltrada = ($this->ed255_i_aguafiltrada == ""?@$GLOBALS["HTTP_POST_VARS"]["ed255_i_aguafiltrada"]:$this->ed255_i_aguafiltrada);
       $this->ed255_c_esgotosanitario = ($this->ed255_c_esgotosanitario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed255_c_esgotosanitario"]:$this->ed255_c_esgotosanitario);
       $this->ed255_c_destinolixo = ($this->ed255_c_destinolixo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed255_c_destinolixo"]:$this->ed255_c_destinolixo);
       $this->ed255_c_localizacao = ($this->ed255_c_localizacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed255_c_localizacao"]:$this->ed255_c_localizacao);
       $this->ed255_c_dependencias = ($this->ed255_c_dependencias == ""?@$GLOBALS["HTTP_POST_VARS"]["ed255_c_dependencias"]:$this->ed255_c_dependencias);
       $this->ed255_c_equipamentos = ($this->ed255_c_equipamentos == ""?@$GLOBALS["HTTP_POST_VARS"]["ed255_c_equipamentos"]:$this->ed255_c_equipamentos);
       $this->ed255_i_computadores = ($this->ed255_i_computadores == ""?@$GLOBALS["HTTP_POST_VARS"]["ed255_i_computadores"]:$this->ed255_i_computadores);
       $this->ed255_i_qtdcomp = ($this->ed255_i_qtdcomp == ""?@$GLOBALS["HTTP_POST_VARS"]["ed255_i_qtdcomp"]:$this->ed255_i_qtdcomp);
       $this->ed255_i_qtdcompadm = ($this->ed255_i_qtdcompadm == ""?@$GLOBALS["HTTP_POST_VARS"]["ed255_i_qtdcompadm"]:$this->ed255_i_qtdcompadm);
       $this->ed255_i_qtdcompalu = ($this->ed255_i_qtdcompalu == ""?@$GLOBALS["HTTP_POST_VARS"]["ed255_i_qtdcompalu"]:$this->ed255_i_qtdcompalu);
       $this->ed255_i_internet = ($this->ed255_i_internet == ""?@$GLOBALS["HTTP_POST_VARS"]["ed255_i_internet"]:$this->ed255_i_internet);
       $this->ed255_i_bandalarga = ($this->ed255_i_bandalarga == ""?@$GLOBALS["HTTP_POST_VARS"]["ed255_i_bandalarga"]:$this->ed255_i_bandalarga);
       $this->ed255_i_alimentacao = ($this->ed255_i_alimentacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed255_i_alimentacao"]:$this->ed255_i_alimentacao);
       $this->ed255_i_ativcomplementar = ($this->ed255_i_ativcomplementar == ""?@$GLOBALS["HTTP_POST_VARS"]["ed255_i_ativcomplementar"]:$this->ed255_i_ativcomplementar);
       $this->ed255_c_materdidatico = ($this->ed255_c_materdidatico == ""?@$GLOBALS["HTTP_POST_VARS"]["ed255_c_materdidatico"]:$this->ed255_c_materdidatico);
       $this->ed255_i_aee = ($this->ed255_i_aee == ""?@$GLOBALS["HTTP_POST_VARS"]["ed255_i_aee"]:$this->ed255_i_aee);
       $this->ed255_i_efciclos = ($this->ed255_i_efciclos == ""?@$GLOBALS["HTTP_POST_VARS"]["ed255_i_efciclos"]:$this->ed255_i_efciclos);
       $this->ed255_i_formaocupacao = ($this->ed255_i_formaocupacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed255_i_formaocupacao"]:$this->ed255_i_formaocupacao);
     }else{
       $this->ed255_i_codigo = ($this->ed255_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed255_i_codigo"]:$this->ed255_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed255_i_codigo){ 
      $this->atualizacampos();
     if($this->ed255_i_escola == null ){ 
       $this->erro_sql = " Campo Código da Escola nao Informado.";
       $this->erro_campo = "ed255_i_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed255_i_compartilhado == null ){ 
       $this->erro_sql = " Campo Predio compartilhado nao Informado.";
       $this->erro_campo = "ed255_i_compartilhado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed255_i_escolacompartilhada == null ){ 
       $this->ed255_i_escolacompartilhada = "null";
     }
     if($this->ed255_i_salaexistente == null ){ 
       $this->ed255_i_salaexistente = "null";
     }
     if($this->ed255_i_salautilizada == null ){ 
       $this->erro_sql = " Campo N° de Salas Utilizadas como Sala de Aula nao Informado.";
       $this->erro_campo = "ed255_i_salautilizada";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed255_c_abastagua == null ){ 
       $this->erro_sql = " Campo Abastecimento de Água nao Informado.";
       $this->erro_campo = "ed255_c_abastagua";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed255_c_abastenergia == null ){ 
       $this->erro_sql = " Campo Abastecimento de Energia nao Informado.";
       $this->erro_campo = "ed255_c_abastenergia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed255_i_aguafiltrada == null ){ 
       $this->erro_sql = " Campo Água Consumida pelos Alunos nao Informado.";
       $this->erro_campo = "ed255_i_aguafiltrada";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed255_c_esgotosanitario == null ){ 
       $this->erro_sql = " Campo Esgoto Sanitario nao Informado.";
       $this->erro_campo = "ed255_c_esgotosanitario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed255_c_destinolixo == null ){ 
       $this->erro_sql = " Campo Destinação do Lixo nao Informado.";
       $this->erro_campo = "ed255_c_destinolixo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed255_c_localizacao == null ){ 
       $this->erro_sql = " Campo Local de Funcionamento nao Informado.";
       $this->erro_campo = "ed255_c_localizacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed255_c_dependencias == null ){ 
       $this->erro_sql = " Campo Dependências Existentes nao Informado.";
       $this->erro_campo = "ed255_c_dependencias";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed255_i_computadores == null ){ 
       $this->erro_sql = " Campo Computadores nao Informado.";
       $this->erro_campo = "ed255_i_computadores";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed255_i_qtdcomp == null ){ 
       $this->ed255_i_qtdcomp = "null";
     }
     if($this->ed255_i_qtdcompadm == null ){ 
       $this->ed255_i_qtdcompadm = "null";
     }
     if($this->ed255_i_qtdcompalu == null ){ 
       $this->ed255_i_qtdcompalu = "null";
     }
     if($this->ed255_i_internet == null ){ 
       $this->ed255_i_internet = "null";
     }
     if($this->ed255_i_bandalarga == null ){ 
       $this->ed255_i_bandalarga = "null";
     }
     if($this->ed255_i_alimentacao == null ){ 
       $this->erro_sql = " Campo Alimentação Escolar para os Alunos nao Informado.";
       $this->erro_campo = "ed255_i_alimentacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed255_i_ativcomplementar == null ){ 
       $this->erro_sql = " Campo Atividade Complementar nao Informado.";
       $this->erro_campo = "ed255_i_ativcomplementar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed255_c_materdidatico == null ){ 
       $this->erro_sql = " Campo Materais Didáticos Específicos nao Informado.";
       $this->erro_campo = "ed255_c_materdidatico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed255_i_aee == null ){ 
       $this->erro_sql = " Campo Atendimento Educ. Especializado AEE nao Informado.";
       $this->erro_campo = "ed255_i_aee";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed255_i_efciclos == null ){ 
       $this->erro_sql = " Campo Ensino Fundamental em ciclos nao Informado.";
       $this->erro_campo = "ed255_i_efciclos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed255_i_formaocupacao == null ){ 
       $this->ed255_i_formaocupacao = "0";
     }
     if($ed255_i_codigo == "" || $ed255_i_codigo == null ){
       $result = db_query("select nextval('escolaestrutura_ed255_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: escolaestrutura_ed255_i_codigo_seq do campo: ed255_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed255_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from escolaestrutura_ed255_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed255_i_codigo)){
         $this->erro_sql = " Campo ed255_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed255_i_codigo = $ed255_i_codigo; 
       }
     }
     if(($this->ed255_i_codigo == null) || ($this->ed255_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed255_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into escolaestrutura(
                                       ed255_i_codigo 
                                      ,ed255_i_escola 
                                      ,ed255_i_compartilhado 
                                      ,ed255_i_escolacompartilhada 
                                      ,ed255_i_salaexistente 
                                      ,ed255_i_salautilizada 
                                      ,ed255_c_abastagua 
                                      ,ed255_c_abastenergia 
                                      ,ed255_i_aguafiltrada 
                                      ,ed255_c_esgotosanitario 
                                      ,ed255_c_destinolixo 
                                      ,ed255_c_localizacao 
                                      ,ed255_c_dependencias 
                                      ,ed255_c_equipamentos 
                                      ,ed255_i_computadores 
                                      ,ed255_i_qtdcomp 
                                      ,ed255_i_qtdcompadm 
                                      ,ed255_i_qtdcompalu 
                                      ,ed255_i_internet 
                                      ,ed255_i_bandalarga 
                                      ,ed255_i_alimentacao 
                                      ,ed255_i_ativcomplementar 
                                      ,ed255_c_materdidatico 
                                      ,ed255_i_aee 
                                      ,ed255_i_efciclos 
                                      ,ed255_i_formaocupacao 
                       )
                values (
                                $this->ed255_i_codigo 
                               ,$this->ed255_i_escola 
                               ,$this->ed255_i_compartilhado 
                               ,$this->ed255_i_escolacompartilhada 
                               ,$this->ed255_i_salaexistente 
                               ,$this->ed255_i_salautilizada 
                               ,'$this->ed255_c_abastagua' 
                               ,'$this->ed255_c_abastenergia' 
                               ,$this->ed255_i_aguafiltrada 
                               ,'$this->ed255_c_esgotosanitario' 
                               ,'$this->ed255_c_destinolixo' 
                               ,'$this->ed255_c_localizacao' 
                               ,'$this->ed255_c_dependencias' 
                               ,'$this->ed255_c_equipamentos' 
                               ,$this->ed255_i_computadores 
                               ,$this->ed255_i_qtdcomp 
                               ,$this->ed255_i_qtdcompadm 
                               ,$this->ed255_i_qtdcompalu 
                               ,$this->ed255_i_internet 
                               ,$this->ed255_i_bandalarga 
                               ,$this->ed255_i_alimentacao 
                               ,$this->ed255_i_ativcomplementar 
                               ,'$this->ed255_c_materdidatico' 
                               ,$this->ed255_i_aee 
                               ,$this->ed255_i_efciclos 
                               ,$this->ed255_i_formaocupacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Infraestrutura da Escola ($this->ed255_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Infraestrutura da Escola já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Infraestrutura da Escola ($this->ed255_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed255_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed255_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12646,'$this->ed255_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2205,12646,'','".AddSlashes(pg_result($resaco,0,'ed255_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2205,12651,'','".AddSlashes(pg_result($resaco,0,'ed255_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2205,12627,'','".AddSlashes(pg_result($resaco,0,'ed255_i_compartilhado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2205,12628,'','".AddSlashes(pg_result($resaco,0,'ed255_i_escolacompartilhada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2205,12629,'','".AddSlashes(pg_result($resaco,0,'ed255_i_salaexistente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2205,12630,'','".AddSlashes(pg_result($resaco,0,'ed255_i_salautilizada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2205,12631,'','".AddSlashes(pg_result($resaco,0,'ed255_c_abastagua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2205,12632,'','".AddSlashes(pg_result($resaco,0,'ed255_c_abastenergia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2205,12633,'','".AddSlashes(pg_result($resaco,0,'ed255_i_aguafiltrada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2205,12634,'','".AddSlashes(pg_result($resaco,0,'ed255_c_esgotosanitario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2205,12635,'','".AddSlashes(pg_result($resaco,0,'ed255_c_destinolixo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2205,13402,'','".AddSlashes(pg_result($resaco,0,'ed255_c_localizacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2205,13400,'','".AddSlashes(pg_result($resaco,0,'ed255_c_dependencias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2205,13403,'','".AddSlashes(pg_result($resaco,0,'ed255_c_equipamentos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2205,13401,'','".AddSlashes(pg_result($resaco,0,'ed255_i_computadores'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2205,13404,'','".AddSlashes(pg_result($resaco,0,'ed255_i_qtdcomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2205,13405,'','".AddSlashes(pg_result($resaco,0,'ed255_i_qtdcompadm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2205,13406,'','".AddSlashes(pg_result($resaco,0,'ed255_i_qtdcompalu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2205,13407,'','".AddSlashes(pg_result($resaco,0,'ed255_i_internet'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2205,13408,'','".AddSlashes(pg_result($resaco,0,'ed255_i_bandalarga'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2205,13409,'','".AddSlashes(pg_result($resaco,0,'ed255_i_alimentacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2205,13410,'','".AddSlashes(pg_result($resaco,0,'ed255_i_ativcomplementar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2205,13411,'','".AddSlashes(pg_result($resaco,0,'ed255_c_materdidatico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2205,14081,'','".AddSlashes(pg_result($resaco,0,'ed255_i_aee'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2205,14082,'','".AddSlashes(pg_result($resaco,0,'ed255_i_efciclos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2205,17987,'','".AddSlashes(pg_result($resaco,0,'ed255_i_formaocupacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed255_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update escolaestrutura set ";
     $virgula = "";
     if(trim($this->ed255_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_codigo"])){ 
       $sql  .= $virgula." ed255_i_codigo = $this->ed255_i_codigo ";
       $virgula = ",";
       if(trim($this->ed255_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed255_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed255_i_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_escola"])){ 
       $sql  .= $virgula." ed255_i_escola = $this->ed255_i_escola ";
       $virgula = ",";
       if(trim($this->ed255_i_escola) == null ){ 
         $this->erro_sql = " Campo Código da Escola nao Informado.";
         $this->erro_campo = "ed255_i_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed255_i_compartilhado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_compartilhado"])){ 
       $sql  .= $virgula." ed255_i_compartilhado = $this->ed255_i_compartilhado ";
       $virgula = ",";
       if(trim($this->ed255_i_compartilhado) == null ){ 
         $this->erro_sql = " Campo Predio compartilhado nao Informado.";
         $this->erro_campo = "ed255_i_compartilhado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed255_i_escolacompartilhada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_escolacompartilhada"])){ 
        if(trim($this->ed255_i_escolacompartilhada)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_escolacompartilhada"])){ 
           $this->ed255_i_escolacompartilhada = "0" ; 
        } 
       $sql  .= $virgula." ed255_i_escolacompartilhada = $this->ed255_i_escolacompartilhada ";
       $virgula = ",";
     }
     if(trim($this->ed255_i_salaexistente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_salaexistente"])){ 
        if(trim($this->ed255_i_salaexistente)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_salaexistente"])){ 
           $this->ed255_i_salaexistente = "0" ; 
        } 
       $sql  .= $virgula." ed255_i_salaexistente = $this->ed255_i_salaexistente ";
       $virgula = ",";
     }
     if(trim($this->ed255_i_salautilizada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_salautilizada"])){ 
       $sql  .= $virgula." ed255_i_salautilizada = $this->ed255_i_salautilizada ";
       $virgula = ",";
       if(trim($this->ed255_i_salautilizada) == null ){ 
         $this->erro_sql = " Campo N° de Salas Utilizadas como Sala de Aula nao Informado.";
         $this->erro_campo = "ed255_i_salautilizada";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed255_c_abastagua)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed255_c_abastagua"])){ 
       $sql  .= $virgula." ed255_c_abastagua = '$this->ed255_c_abastagua' ";
       $virgula = ",";
       if(trim($this->ed255_c_abastagua) == null ){ 
         $this->erro_sql = " Campo Abastecimento de Água nao Informado.";
         $this->erro_campo = "ed255_c_abastagua";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed255_c_abastenergia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed255_c_abastenergia"])){ 
       $sql  .= $virgula." ed255_c_abastenergia = '$this->ed255_c_abastenergia' ";
       $virgula = ",";
       if(trim($this->ed255_c_abastenergia) == null ){ 
         $this->erro_sql = " Campo Abastecimento de Energia nao Informado.";
         $this->erro_campo = "ed255_c_abastenergia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed255_i_aguafiltrada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_aguafiltrada"])){ 
       $sql  .= $virgula." ed255_i_aguafiltrada = $this->ed255_i_aguafiltrada ";
       $virgula = ",";
       if(trim($this->ed255_i_aguafiltrada) == null ){ 
         $this->erro_sql = " Campo Água Consumida pelos Alunos nao Informado.";
         $this->erro_campo = "ed255_i_aguafiltrada";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed255_c_esgotosanitario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed255_c_esgotosanitario"])){ 
       $sql  .= $virgula." ed255_c_esgotosanitario = '$this->ed255_c_esgotosanitario' ";
       $virgula = ",";
       if(trim($this->ed255_c_esgotosanitario) == null ){ 
         $this->erro_sql = " Campo Esgoto Sanitario nao Informado.";
         $this->erro_campo = "ed255_c_esgotosanitario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed255_c_destinolixo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed255_c_destinolixo"])){ 
       $sql  .= $virgula." ed255_c_destinolixo = '$this->ed255_c_destinolixo' ";
       $virgula = ",";
       if(trim($this->ed255_c_destinolixo) == null ){ 
         $this->erro_sql = " Campo Destinação do Lixo nao Informado.";
         $this->erro_campo = "ed255_c_destinolixo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed255_c_localizacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed255_c_localizacao"])){ 
       $sql  .= $virgula." ed255_c_localizacao = '$this->ed255_c_localizacao' ";
       $virgula = ",";
       if(trim($this->ed255_c_localizacao) == null ){ 
         $this->erro_sql = " Campo Local de Funcionamento nao Informado.";
         $this->erro_campo = "ed255_c_localizacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed255_c_dependencias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed255_c_dependencias"])){ 
       $sql  .= $virgula." ed255_c_dependencias = '$this->ed255_c_dependencias' ";
       $virgula = ",";
       if(trim($this->ed255_c_dependencias) == null ){ 
         $this->erro_sql = " Campo Dependências Existentes nao Informado.";
         $this->erro_campo = "ed255_c_dependencias";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed255_c_equipamentos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed255_c_equipamentos"])){ 
       $sql  .= $virgula." ed255_c_equipamentos = '$this->ed255_c_equipamentos' ";
       $virgula = ",";
     }
     if(trim($this->ed255_i_computadores)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_computadores"])){ 
       $sql  .= $virgula." ed255_i_computadores = $this->ed255_i_computadores ";
       $virgula = ",";
       if(trim($this->ed255_i_computadores) == null ){ 
         $this->erro_sql = " Campo Computadores nao Informado.";
         $this->erro_campo = "ed255_i_computadores";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed255_i_qtdcomp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_qtdcomp"])){ 
        if(trim($this->ed255_i_qtdcomp)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_qtdcomp"])){ 
           $this->ed255_i_qtdcomp = "0" ; 
        } 
       $sql  .= $virgula." ed255_i_qtdcomp = $this->ed255_i_qtdcomp ";
       $virgula = ",";
     }
     if(trim($this->ed255_i_qtdcompadm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_qtdcompadm"])){ 
        if(trim($this->ed255_i_qtdcompadm)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_qtdcompadm"])){ 
           $this->ed255_i_qtdcompadm = "0" ; 
        } 
       $sql  .= $virgula." ed255_i_qtdcompadm = $this->ed255_i_qtdcompadm ";
       $virgula = ",";
     }
     if(trim($this->ed255_i_qtdcompalu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_qtdcompalu"])){ 
        if(trim($this->ed255_i_qtdcompalu)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_qtdcompalu"])){ 
           $this->ed255_i_qtdcompalu = "0" ; 
        } 
       $sql  .= $virgula." ed255_i_qtdcompalu = $this->ed255_i_qtdcompalu ";
       $virgula = ",";
     }
     if(trim($this->ed255_i_internet)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_internet"])){ 
        if(trim($this->ed255_i_internet)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_internet"])){ 
           $this->ed255_i_internet = "0" ; 
        } 
       $sql  .= $virgula." ed255_i_internet = $this->ed255_i_internet ";
       $virgula = ",";
     }
     if(trim($this->ed255_i_bandalarga)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_bandalarga"])){ 
        if(trim($this->ed255_i_bandalarga)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_bandalarga"])){ 
           $this->ed255_i_bandalarga = "0" ; 
        } 
       $sql  .= $virgula." ed255_i_bandalarga = $this->ed255_i_bandalarga ";
       $virgula = ",";
     }
     if(trim($this->ed255_i_alimentacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_alimentacao"])){ 
       $sql  .= $virgula." ed255_i_alimentacao = $this->ed255_i_alimentacao ";
       $virgula = ",";
       if(trim($this->ed255_i_alimentacao) == null ){ 
         $this->erro_sql = " Campo Alimentação Escolar para os Alunos nao Informado.";
         $this->erro_campo = "ed255_i_alimentacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed255_i_ativcomplementar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_ativcomplementar"])){ 
       $sql  .= $virgula." ed255_i_ativcomplementar = $this->ed255_i_ativcomplementar ";
       $virgula = ",";
       if(trim($this->ed255_i_ativcomplementar) == null ){ 
         $this->erro_sql = " Campo Atividade Complementar nao Informado.";
         $this->erro_campo = "ed255_i_ativcomplementar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed255_c_materdidatico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed255_c_materdidatico"])){ 
       $sql  .= $virgula." ed255_c_materdidatico = '$this->ed255_c_materdidatico' ";
       $virgula = ",";
       if(trim($this->ed255_c_materdidatico) == null ){ 
         $this->erro_sql = " Campo Materais Didáticos Específicos nao Informado.";
         $this->erro_campo = "ed255_c_materdidatico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed255_i_aee)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_aee"])){ 
       $sql  .= $virgula." ed255_i_aee = $this->ed255_i_aee ";
       $virgula = ",";
       if(trim($this->ed255_i_aee) == null ){ 
         $this->erro_sql = " Campo Atendimento Educ. Especializado AEE nao Informado.";
         $this->erro_campo = "ed255_i_aee";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed255_i_efciclos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_efciclos"])){ 
       $sql  .= $virgula." ed255_i_efciclos = $this->ed255_i_efciclos ";
       $virgula = ",";
       if(trim($this->ed255_i_efciclos) == null ){ 
         $this->erro_sql = " Campo Ensino Fundamental em ciclos nao Informado.";
         $this->erro_campo = "ed255_i_efciclos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed255_i_formaocupacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_formaocupacao"])){ 
        if(trim($this->ed255_i_formaocupacao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_formaocupacao"])){ 
           $this->ed255_i_formaocupacao = "0" ; 
        } 
       $sql  .= $virgula." ed255_i_formaocupacao = $this->ed255_i_formaocupacao ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed255_i_codigo!=null){
       $sql .= " ed255_i_codigo = $this->ed255_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed255_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12646,'$this->ed255_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_codigo"]) || $this->ed255_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2205,12646,'".AddSlashes(pg_result($resaco,$conresaco,'ed255_i_codigo'))."','$this->ed255_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_escola"]) || $this->ed255_i_escola != "")
           $resac = db_query("insert into db_acount values($acount,2205,12651,'".AddSlashes(pg_result($resaco,$conresaco,'ed255_i_escola'))."','$this->ed255_i_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_compartilhado"]) || $this->ed255_i_compartilhado != "")
           $resac = db_query("insert into db_acount values($acount,2205,12627,'".AddSlashes(pg_result($resaco,$conresaco,'ed255_i_compartilhado'))."','$this->ed255_i_compartilhado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_escolacompartilhada"]) || $this->ed255_i_escolacompartilhada != "")
           $resac = db_query("insert into db_acount values($acount,2205,12628,'".AddSlashes(pg_result($resaco,$conresaco,'ed255_i_escolacompartilhada'))."','$this->ed255_i_escolacompartilhada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_salaexistente"]) || $this->ed255_i_salaexistente != "")
           $resac = db_query("insert into db_acount values($acount,2205,12629,'".AddSlashes(pg_result($resaco,$conresaco,'ed255_i_salaexistente'))."','$this->ed255_i_salaexistente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_salautilizada"]) || $this->ed255_i_salautilizada != "")
           $resac = db_query("insert into db_acount values($acount,2205,12630,'".AddSlashes(pg_result($resaco,$conresaco,'ed255_i_salautilizada'))."','$this->ed255_i_salautilizada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed255_c_abastagua"]) || $this->ed255_c_abastagua != "")
           $resac = db_query("insert into db_acount values($acount,2205,12631,'".AddSlashes(pg_result($resaco,$conresaco,'ed255_c_abastagua'))."','$this->ed255_c_abastagua',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed255_c_abastenergia"]) || $this->ed255_c_abastenergia != "")
           $resac = db_query("insert into db_acount values($acount,2205,12632,'".AddSlashes(pg_result($resaco,$conresaco,'ed255_c_abastenergia'))."','$this->ed255_c_abastenergia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_aguafiltrada"]) || $this->ed255_i_aguafiltrada != "")
           $resac = db_query("insert into db_acount values($acount,2205,12633,'".AddSlashes(pg_result($resaco,$conresaco,'ed255_i_aguafiltrada'))."','$this->ed255_i_aguafiltrada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed255_c_esgotosanitario"]) || $this->ed255_c_esgotosanitario != "")
           $resac = db_query("insert into db_acount values($acount,2205,12634,'".AddSlashes(pg_result($resaco,$conresaco,'ed255_c_esgotosanitario'))."','$this->ed255_c_esgotosanitario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed255_c_destinolixo"]) || $this->ed255_c_destinolixo != "")
           $resac = db_query("insert into db_acount values($acount,2205,12635,'".AddSlashes(pg_result($resaco,$conresaco,'ed255_c_destinolixo'))."','$this->ed255_c_destinolixo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed255_c_localizacao"]) || $this->ed255_c_localizacao != "")
           $resac = db_query("insert into db_acount values($acount,2205,13402,'".AddSlashes(pg_result($resaco,$conresaco,'ed255_c_localizacao'))."','$this->ed255_c_localizacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed255_c_dependencias"]) || $this->ed255_c_dependencias != "")
           $resac = db_query("insert into db_acount values($acount,2205,13400,'".AddSlashes(pg_result($resaco,$conresaco,'ed255_c_dependencias'))."','$this->ed255_c_dependencias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed255_c_equipamentos"]) || $this->ed255_c_equipamentos != "")
           $resac = db_query("insert into db_acount values($acount,2205,13403,'".AddSlashes(pg_result($resaco,$conresaco,'ed255_c_equipamentos'))."','$this->ed255_c_equipamentos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_computadores"]) || $this->ed255_i_computadores != "")
           $resac = db_query("insert into db_acount values($acount,2205,13401,'".AddSlashes(pg_result($resaco,$conresaco,'ed255_i_computadores'))."','$this->ed255_i_computadores',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_qtdcomp"]) || $this->ed255_i_qtdcomp != "")
           $resac = db_query("insert into db_acount values($acount,2205,13404,'".AddSlashes(pg_result($resaco,$conresaco,'ed255_i_qtdcomp'))."','$this->ed255_i_qtdcomp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_qtdcompadm"]) || $this->ed255_i_qtdcompadm != "")
           $resac = db_query("insert into db_acount values($acount,2205,13405,'".AddSlashes(pg_result($resaco,$conresaco,'ed255_i_qtdcompadm'))."','$this->ed255_i_qtdcompadm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_qtdcompalu"]) || $this->ed255_i_qtdcompalu != "")
           $resac = db_query("insert into db_acount values($acount,2205,13406,'".AddSlashes(pg_result($resaco,$conresaco,'ed255_i_qtdcompalu'))."','$this->ed255_i_qtdcompalu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_internet"]) || $this->ed255_i_internet != "")
           $resac = db_query("insert into db_acount values($acount,2205,13407,'".AddSlashes(pg_result($resaco,$conresaco,'ed255_i_internet'))."','$this->ed255_i_internet',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_bandalarga"]) || $this->ed255_i_bandalarga != "")
           $resac = db_query("insert into db_acount values($acount,2205,13408,'".AddSlashes(pg_result($resaco,$conresaco,'ed255_i_bandalarga'))."','$this->ed255_i_bandalarga',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_alimentacao"]) || $this->ed255_i_alimentacao != "")
           $resac = db_query("insert into db_acount values($acount,2205,13409,'".AddSlashes(pg_result($resaco,$conresaco,'ed255_i_alimentacao'))."','$this->ed255_i_alimentacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_ativcomplementar"]) || $this->ed255_i_ativcomplementar != "")
           $resac = db_query("insert into db_acount values($acount,2205,13410,'".AddSlashes(pg_result($resaco,$conresaco,'ed255_i_ativcomplementar'))."','$this->ed255_i_ativcomplementar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed255_c_materdidatico"]) || $this->ed255_c_materdidatico != "")
           $resac = db_query("insert into db_acount values($acount,2205,13411,'".AddSlashes(pg_result($resaco,$conresaco,'ed255_c_materdidatico'))."','$this->ed255_c_materdidatico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_aee"]) || $this->ed255_i_aee != "")
           $resac = db_query("insert into db_acount values($acount,2205,14081,'".AddSlashes(pg_result($resaco,$conresaco,'ed255_i_aee'))."','$this->ed255_i_aee',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_efciclos"]) || $this->ed255_i_efciclos != "")
           $resac = db_query("insert into db_acount values($acount,2205,14082,'".AddSlashes(pg_result($resaco,$conresaco,'ed255_i_efciclos'))."','$this->ed255_i_efciclos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed255_i_formaocupacao"]) || $this->ed255_i_formaocupacao != "")
           $resac = db_query("insert into db_acount values($acount,2205,17987,'".AddSlashes(pg_result($resaco,$conresaco,'ed255_i_formaocupacao'))."','$this->ed255_i_formaocupacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Infraestrutura da Escola nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed255_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Infraestrutura da Escola nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed255_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed255_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed255_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed255_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12646,'$ed255_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2205,12646,'','".AddSlashes(pg_result($resaco,$iresaco,'ed255_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2205,12651,'','".AddSlashes(pg_result($resaco,$iresaco,'ed255_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2205,12627,'','".AddSlashes(pg_result($resaco,$iresaco,'ed255_i_compartilhado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2205,12628,'','".AddSlashes(pg_result($resaco,$iresaco,'ed255_i_escolacompartilhada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2205,12629,'','".AddSlashes(pg_result($resaco,$iresaco,'ed255_i_salaexistente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2205,12630,'','".AddSlashes(pg_result($resaco,$iresaco,'ed255_i_salautilizada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2205,12631,'','".AddSlashes(pg_result($resaco,$iresaco,'ed255_c_abastagua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2205,12632,'','".AddSlashes(pg_result($resaco,$iresaco,'ed255_c_abastenergia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2205,12633,'','".AddSlashes(pg_result($resaco,$iresaco,'ed255_i_aguafiltrada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2205,12634,'','".AddSlashes(pg_result($resaco,$iresaco,'ed255_c_esgotosanitario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2205,12635,'','".AddSlashes(pg_result($resaco,$iresaco,'ed255_c_destinolixo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2205,13402,'','".AddSlashes(pg_result($resaco,$iresaco,'ed255_c_localizacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2205,13400,'','".AddSlashes(pg_result($resaco,$iresaco,'ed255_c_dependencias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2205,13403,'','".AddSlashes(pg_result($resaco,$iresaco,'ed255_c_equipamentos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2205,13401,'','".AddSlashes(pg_result($resaco,$iresaco,'ed255_i_computadores'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2205,13404,'','".AddSlashes(pg_result($resaco,$iresaco,'ed255_i_qtdcomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2205,13405,'','".AddSlashes(pg_result($resaco,$iresaco,'ed255_i_qtdcompadm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2205,13406,'','".AddSlashes(pg_result($resaco,$iresaco,'ed255_i_qtdcompalu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2205,13407,'','".AddSlashes(pg_result($resaco,$iresaco,'ed255_i_internet'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2205,13408,'','".AddSlashes(pg_result($resaco,$iresaco,'ed255_i_bandalarga'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2205,13409,'','".AddSlashes(pg_result($resaco,$iresaco,'ed255_i_alimentacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2205,13410,'','".AddSlashes(pg_result($resaco,$iresaco,'ed255_i_ativcomplementar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2205,13411,'','".AddSlashes(pg_result($resaco,$iresaco,'ed255_c_materdidatico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2205,14081,'','".AddSlashes(pg_result($resaco,$iresaco,'ed255_i_aee'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2205,14082,'','".AddSlashes(pg_result($resaco,$iresaco,'ed255_i_efciclos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2205,17987,'','".AddSlashes(pg_result($resaco,$iresaco,'ed255_i_formaocupacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from escolaestrutura
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed255_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed255_i_codigo = $ed255_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Infraestrutura da Escola nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed255_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Infraestrutura da Escola nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed255_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed255_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:escolaestrutura";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
 // funcao do sql 
   function sql_query ( $ed255_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from escolaestrutura ";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = escolaestrutura.ed255_i_escola";
     $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql .= "      inner join censouf  on  censouf.ed260_i_codigo = escola.ed18_i_censouf";
     $sql .= "      inner join censomunic  on  censomunic.ed261_i_codigo = escola.ed18_i_censomunic";
     $sql .= "      left join censodistrito  on  censodistrito.ed262_i_codigo = escola.ed18_i_censodistrito";
     $sql .= "      left  join censoorgreg  on  censoorgreg.ed263_i_codigo = escola.ed18_i_censoorgreg";
     $sql .= "      left  join censolinguaindig  on  censolinguaindig.ed264_i_codigo = escola.ed18_i_linguaindigena";
     $sql2 = "";
     if($dbwhere==""){
       if($ed255_i_codigo!=null ){
         $sql2 .= " where escolaestrutura.ed255_i_codigo = $ed255_i_codigo "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   // funcao do sql 
   function sql_query_file ( $ed255_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from escolaestrutura ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed255_i_codigo!=null ){
         $sql2 .= " where escolaestrutura.ed255_i_codigo = $ed255_i_codigo "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>