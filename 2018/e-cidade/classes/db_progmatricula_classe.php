<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: educação
//CLASSE DA ENTIDADE progmatricula
class cl_progmatricula { 
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
   var $ed112_i_codigo = 0; 
   var $ed112_i_rhpessoal = 0; 
   var $ed112_i_progclasse = 0; 
   var $ed112_i_nivel = 0; 
   var $ed112_i_usuario = 0; 
   var $ed112_d_database_dia = null; 
   var $ed112_d_database_mes = null; 
   var $ed112_d_database_ano = null; 
   var $ed112_d_database = null; 
   var $ed112_d_datainicio_dia = null; 
   var $ed112_d_datainicio_mes = null; 
   var $ed112_d_datainicio_ano = null; 
   var $ed112_d_datainicio = null; 
   var $ed112_c_situacao = null; 
   var $ed112_d_datafinal_dia = null; 
   var $ed112_d_datafinal_mes = null; 
   var $ed112_d_datafinal_ano = null; 
   var $ed112_d_datafinal = null; 
   var $ed112_c_classeesp = null; 
   var $ed112_c_dedicacao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed112_i_codigo = int8 = Código 
                 ed112_i_rhpessoal = int8 = Matrícula 
                 ed112_i_progclasse = int8 = Classe 
                 ed112_i_nivel = int8 = Nível 
                 ed112_i_usuario = int8 = Usuário 
                 ed112_d_database = date = Data Admissão 
                 ed112_d_datainicio = date = Data de Inicio na Classe 
                 ed112_c_situacao = char(1) = Situação 
                 ed112_d_datafinal = date = Data de Encerramento 
                 ed112_c_classeesp = char(1) = Classe Especial 
                 ed112_c_dedicacao = char(1) = Dedicação Docente 
                 ";
   //funcao construtor da classe 
   function cl_progmatricula() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("progmatricula"); 
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
       $this->ed112_i_codigo = ($this->ed112_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed112_i_codigo"]:$this->ed112_i_codigo);
       $this->ed112_i_rhpessoal = ($this->ed112_i_rhpessoal == ""?@$GLOBALS["HTTP_POST_VARS"]["ed112_i_rhpessoal"]:$this->ed112_i_rhpessoal);
       $this->ed112_i_progclasse = ($this->ed112_i_progclasse == ""?@$GLOBALS["HTTP_POST_VARS"]["ed112_i_progclasse"]:$this->ed112_i_progclasse);
       $this->ed112_i_nivel = ($this->ed112_i_nivel == ""?@$GLOBALS["HTTP_POST_VARS"]["ed112_i_nivel"]:$this->ed112_i_nivel);
       $this->ed112_i_usuario = ($this->ed112_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed112_i_usuario"]:$this->ed112_i_usuario);
       if($this->ed112_d_database == ""){
         $this->ed112_d_database_dia = ($this->ed112_d_database_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed112_d_database_dia"]:$this->ed112_d_database_dia);
         $this->ed112_d_database_mes = ($this->ed112_d_database_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed112_d_database_mes"]:$this->ed112_d_database_mes);
         $this->ed112_d_database_ano = ($this->ed112_d_database_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed112_d_database_ano"]:$this->ed112_d_database_ano);
         if($this->ed112_d_database_dia != ""){
            $this->ed112_d_database = $this->ed112_d_database_ano."-".$this->ed112_d_database_mes."-".$this->ed112_d_database_dia;
         }
       }
       if($this->ed112_d_datainicio == ""){
         $this->ed112_d_datainicio_dia = ($this->ed112_d_datainicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed112_d_datainicio_dia"]:$this->ed112_d_datainicio_dia);
         $this->ed112_d_datainicio_mes = ($this->ed112_d_datainicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed112_d_datainicio_mes"]:$this->ed112_d_datainicio_mes);
         $this->ed112_d_datainicio_ano = ($this->ed112_d_datainicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed112_d_datainicio_ano"]:$this->ed112_d_datainicio_ano);
         if($this->ed112_d_datainicio_dia != ""){
            $this->ed112_d_datainicio = $this->ed112_d_datainicio_ano."-".$this->ed112_d_datainicio_mes."-".$this->ed112_d_datainicio_dia;
         }
       }
       $this->ed112_c_situacao = ($this->ed112_c_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed112_c_situacao"]:$this->ed112_c_situacao);
       if($this->ed112_d_datafinal == ""){
         $this->ed112_d_datafinal_dia = ($this->ed112_d_datafinal_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed112_d_datafinal_dia"]:$this->ed112_d_datafinal_dia);
         $this->ed112_d_datafinal_mes = ($this->ed112_d_datafinal_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed112_d_datafinal_mes"]:$this->ed112_d_datafinal_mes);
         $this->ed112_d_datafinal_ano = ($this->ed112_d_datafinal_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed112_d_datafinal_ano"]:$this->ed112_d_datafinal_ano);
         if($this->ed112_d_datafinal_dia != ""){
            $this->ed112_d_datafinal = $this->ed112_d_datafinal_ano."-".$this->ed112_d_datafinal_mes."-".$this->ed112_d_datafinal_dia;
         }
       }
       $this->ed112_c_classeesp = ($this->ed112_c_classeesp == ""?@$GLOBALS["HTTP_POST_VARS"]["ed112_c_classeesp"]:$this->ed112_c_classeesp);
       $this->ed112_c_dedicacao = ($this->ed112_c_dedicacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed112_c_dedicacao"]:$this->ed112_c_dedicacao);
     }else{
       $this->ed112_i_codigo = ($this->ed112_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed112_i_codigo"]:$this->ed112_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed112_i_codigo){ 
      $this->atualizacampos();
     if($this->ed112_i_rhpessoal == null ){ 
       $this->erro_sql = " Campo Matrícula nao Informado.";
       $this->erro_campo = "ed112_i_rhpessoal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed112_i_progclasse == null ){ 
       $this->erro_sql = " Campo Classe nao Informado.";
       $this->erro_campo = "ed112_i_progclasse";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed112_i_nivel == null ){ 
       $this->erro_sql = " Campo Nível nao Informado.";
       $this->erro_campo = "ed112_i_nivel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed112_i_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "ed112_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed112_d_database == null ){ 
       $this->erro_sql = " Campo Data Admissão nao Informado.";
       $this->erro_campo = "ed112_d_database_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed112_d_datainicio == null ){ 
       $this->erro_sql = " Campo Data de Inicio na Classe nao Informado.";
       $this->erro_campo = "ed112_d_datainicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed112_c_situacao == null ){ 
       $this->erro_sql = " Campo Situação nao Informado.";
       $this->erro_campo = "ed112_c_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed112_d_datafinal == null ){ 
       $this->ed112_d_datafinal = "null";
     }
     if($this->ed112_c_classeesp == null ){ 
       $this->erro_sql = " Campo Classe Especial nao Informado.";
       $this->erro_campo = "ed112_c_classeesp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed112_c_dedicacao == null ){ 
       $this->erro_sql = " Campo Dedicação Docente nao Informado.";
       $this->erro_campo = "ed112_c_dedicacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed112_i_codigo == "" || $ed112_i_codigo == null ){
       $result = db_query("select nextval('progmatricula_ed112_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: progmatricula_ed112_i_codigo_seq do campo: ed112_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed112_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from progmatricula_ed112_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed112_i_codigo)){
         $this->erro_sql = " Campo ed112_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed112_i_codigo = $ed112_i_codigo; 
       }
     }
     if(($this->ed112_i_codigo == null) || ($this->ed112_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed112_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into progmatricula(
                                       ed112_i_codigo 
                                      ,ed112_i_rhpessoal 
                                      ,ed112_i_progclasse 
                                      ,ed112_i_nivel 
                                      ,ed112_i_usuario 
                                      ,ed112_d_database 
                                      ,ed112_d_datainicio 
                                      ,ed112_c_situacao 
                                      ,ed112_d_datafinal 
                                      ,ed112_c_classeesp 
                                      ,ed112_c_dedicacao 
                       )
                values (
                                $this->ed112_i_codigo 
                               ,$this->ed112_i_rhpessoal 
                               ,$this->ed112_i_progclasse 
                               ,$this->ed112_i_nivel 
                               ,$this->ed112_i_usuario 
                               ,".($this->ed112_d_database == "null" || $this->ed112_d_database == ""?"null":"'".$this->ed112_d_database."'")." 
                               ,".($this->ed112_d_datainicio == "null" || $this->ed112_d_datainicio == ""?"null":"'".$this->ed112_d_datainicio."'")." 
                               ,'$this->ed112_c_situacao' 
                               ,".($this->ed112_d_datafinal == "null" || $this->ed112_d_datafinal == ""?"null":"'".$this->ed112_d_datafinal."'")." 
                               ,'$this->ed112_c_classeesp' 
                               ,'$this->ed112_c_dedicacao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro da progressão do professor ($this->ed112_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro da progressão do professor já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro da progressão do professor ($this->ed112_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed112_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed112_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1009091,'$this->ed112_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010168,1009091,'','".AddSlashes(pg_result($resaco,0,'ed112_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010168,1009092,'','".AddSlashes(pg_result($resaco,0,'ed112_i_rhpessoal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010168,1009093,'','".AddSlashes(pg_result($resaco,0,'ed112_i_progclasse'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010168,1009188,'','".AddSlashes(pg_result($resaco,0,'ed112_i_nivel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010168,1009094,'','".AddSlashes(pg_result($resaco,0,'ed112_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010168,1009095,'','".AddSlashes(pg_result($resaco,0,'ed112_d_database'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010168,1009096,'','".AddSlashes(pg_result($resaco,0,'ed112_d_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010168,1009097,'','".AddSlashes(pg_result($resaco,0,'ed112_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010168,1009185,'','".AddSlashes(pg_result($resaco,0,'ed112_d_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010168,1009189,'','".AddSlashes(pg_result($resaco,0,'ed112_c_classeesp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010168,1009190,'','".AddSlashes(pg_result($resaco,0,'ed112_c_dedicacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed112_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update progmatricula set ";
     $virgula = "";
     if(trim($this->ed112_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed112_i_codigo"])){ 
       $sql  .= $virgula." ed112_i_codigo = $this->ed112_i_codigo ";
       $virgula = ",";
       if(trim($this->ed112_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed112_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed112_i_rhpessoal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed112_i_rhpessoal"])){ 
       $sql  .= $virgula." ed112_i_rhpessoal = $this->ed112_i_rhpessoal ";
       $virgula = ",";
       if(trim($this->ed112_i_rhpessoal) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "ed112_i_rhpessoal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed112_i_progclasse)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed112_i_progclasse"])){ 
       $sql  .= $virgula." ed112_i_progclasse = $this->ed112_i_progclasse ";
       $virgula = ",";
       if(trim($this->ed112_i_progclasse) == null ){ 
         $this->erro_sql = " Campo Classe nao Informado.";
         $this->erro_campo = "ed112_i_progclasse";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed112_i_nivel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed112_i_nivel"])){ 
       $sql  .= $virgula." ed112_i_nivel = $this->ed112_i_nivel ";
       $virgula = ",";
       if(trim($this->ed112_i_nivel) == null ){ 
         $this->erro_sql = " Campo Nível nao Informado.";
         $this->erro_campo = "ed112_i_nivel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed112_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed112_i_usuario"])){ 
       $sql  .= $virgula." ed112_i_usuario = $this->ed112_i_usuario ";
       $virgula = ",";
       if(trim($this->ed112_i_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "ed112_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed112_d_database)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed112_d_database_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed112_d_database_dia"] !="") ){ 
       $sql  .= $virgula." ed112_d_database = '$this->ed112_d_database' ";
       $virgula = ",";
       if(trim($this->ed112_d_database) == null ){ 
         $this->erro_sql = " Campo Data Admissão nao Informado.";
         $this->erro_campo = "ed112_d_database_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed112_d_database_dia"])){ 
         $sql  .= $virgula." ed112_d_database = null ";
         $virgula = ",";
         if(trim($this->ed112_d_database) == null ){ 
           $this->erro_sql = " Campo Data Admissão nao Informado.";
           $this->erro_campo = "ed112_d_database_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed112_d_datainicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed112_d_datainicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed112_d_datainicio_dia"] !="") ){ 
       $sql  .= $virgula." ed112_d_datainicio = '$this->ed112_d_datainicio' ";
       $virgula = ",";
       if(trim($this->ed112_d_datainicio) == null ){ 
         $this->erro_sql = " Campo Data de Inicio na Classe nao Informado.";
         $this->erro_campo = "ed112_d_datainicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed112_d_datainicio_dia"])){ 
         $sql  .= $virgula." ed112_d_datainicio = null ";
         $virgula = ",";
         if(trim($this->ed112_d_datainicio) == null ){ 
           $this->erro_sql = " Campo Data de Inicio na Classe nao Informado.";
           $this->erro_campo = "ed112_d_datainicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed112_c_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed112_c_situacao"])){ 
       $sql  .= $virgula." ed112_c_situacao = '$this->ed112_c_situacao' ";
       $virgula = ",";
       if(trim($this->ed112_c_situacao) == null ){ 
         $this->erro_sql = " Campo Situação nao Informado.";
         $this->erro_campo = "ed112_c_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed112_d_datafinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed112_d_datafinal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed112_d_datafinal_dia"] !="") ){ 
       $sql  .= $virgula." ed112_d_datafinal = '$this->ed112_d_datafinal' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed112_d_datafinal_dia"])){ 
         $sql  .= $virgula." ed112_d_datafinal = null ";
         $virgula = ",";
       }
     }
     if(trim($this->ed112_c_classeesp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed112_c_classeesp"])){ 
       $sql  .= $virgula." ed112_c_classeesp = '$this->ed112_c_classeesp' ";
       $virgula = ",";
       if(trim($this->ed112_c_classeesp) == null ){ 
         $this->erro_sql = " Campo Classe Especial nao Informado.";
         $this->erro_campo = "ed112_c_classeesp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed112_c_dedicacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed112_c_dedicacao"])){ 
       $sql  .= $virgula." ed112_c_dedicacao = '$this->ed112_c_dedicacao' ";
       $virgula = ",";
       if(trim($this->ed112_c_dedicacao) == null ){ 
         $this->erro_sql = " Campo Dedicação Docente nao Informado.";
         $this->erro_campo = "ed112_c_dedicacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed112_i_codigo!=null){
       $sql .= " ed112_i_codigo = $this->ed112_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed112_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009091,'$this->ed112_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed112_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010168,1009091,'".AddSlashes(pg_result($resaco,$conresaco,'ed112_i_codigo'))."','$this->ed112_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed112_i_rhpessoal"]))
           $resac = db_query("insert into db_acount values($acount,1010168,1009092,'".AddSlashes(pg_result($resaco,$conresaco,'ed112_i_rhpessoal'))."','$this->ed112_i_rhpessoal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed112_i_progclasse"]))
           $resac = db_query("insert into db_acount values($acount,1010168,1009093,'".AddSlashes(pg_result($resaco,$conresaco,'ed112_i_progclasse'))."','$this->ed112_i_progclasse',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed112_i_nivel"]))
           $resac = db_query("insert into db_acount values($acount,1010168,1009188,'".AddSlashes(pg_result($resaco,$conresaco,'ed112_i_nivel'))."','$this->ed112_i_nivel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed112_i_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1010168,1009094,'".AddSlashes(pg_result($resaco,$conresaco,'ed112_i_usuario'))."','$this->ed112_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed112_d_database"]))
           $resac = db_query("insert into db_acount values($acount,1010168,1009095,'".AddSlashes(pg_result($resaco,$conresaco,'ed112_d_database'))."','$this->ed112_d_database',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed112_d_datainicio"]))
           $resac = db_query("insert into db_acount values($acount,1010168,1009096,'".AddSlashes(pg_result($resaco,$conresaco,'ed112_d_datainicio'))."','$this->ed112_d_datainicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed112_c_situacao"]))
           $resac = db_query("insert into db_acount values($acount,1010168,1009097,'".AddSlashes(pg_result($resaco,$conresaco,'ed112_c_situacao'))."','$this->ed112_c_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed112_d_datafinal"]))
           $resac = db_query("insert into db_acount values($acount,1010168,1009185,'".AddSlashes(pg_result($resaco,$conresaco,'ed112_d_datafinal'))."','$this->ed112_d_datafinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed112_c_classeesp"]))
           $resac = db_query("insert into db_acount values($acount,1010168,1009189,'".AddSlashes(pg_result($resaco,$conresaco,'ed112_c_classeesp'))."','$this->ed112_c_classeesp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed112_c_dedicacao"]))
           $resac = db_query("insert into db_acount values($acount,1010168,1009190,'".AddSlashes(pg_result($resaco,$conresaco,'ed112_c_dedicacao'))."','$this->ed112_c_dedicacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro da progressão do professor nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed112_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro da progressão do professor nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed112_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed112_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed112_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed112_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009091,'$ed112_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010168,1009091,'','".AddSlashes(pg_result($resaco,$iresaco,'ed112_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010168,1009092,'','".AddSlashes(pg_result($resaco,$iresaco,'ed112_i_rhpessoal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010168,1009093,'','".AddSlashes(pg_result($resaco,$iresaco,'ed112_i_progclasse'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010168,1009188,'','".AddSlashes(pg_result($resaco,$iresaco,'ed112_i_nivel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010168,1009094,'','".AddSlashes(pg_result($resaco,$iresaco,'ed112_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010168,1009095,'','".AddSlashes(pg_result($resaco,$iresaco,'ed112_d_database'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010168,1009096,'','".AddSlashes(pg_result($resaco,$iresaco,'ed112_d_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010168,1009097,'','".AddSlashes(pg_result($resaco,$iresaco,'ed112_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010168,1009185,'','".AddSlashes(pg_result($resaco,$iresaco,'ed112_d_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010168,1009189,'','".AddSlashes(pg_result($resaco,$iresaco,'ed112_c_classeesp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010168,1009190,'','".AddSlashes(pg_result($resaco,$iresaco,'ed112_c_dedicacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from progmatricula
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed112_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed112_i_codigo = $ed112_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro da progressão do professor nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed112_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro da progressão do professor nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed112_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed112_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:progmatricula";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed112_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from progmatricula ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = progmatricula.ed112_i_usuario";
     $sql .= "      inner join progclasse  on  progclasse.ed107_i_codigo = progmatricula.ed112_i_progclasse";
     $sql .= "      inner join prognivel  on  prognivel.ed124_i_codigo = progmatricula.ed112_i_nivel";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = progmatricula.ed112_i_rhpessoal";
     $sql .= "      inner join db_config  on  db_config.codigo = rhpessoal.rh01_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      inner join rhestcivil  on  rhestcivil.rh08_estciv = rhpessoal.rh01_estciv";
     $sql .= "      inner join rhraca  on  rhraca.rh18_raca = rhpessoal.rh01_raca";
     $sql .= "      inner join rhinstrucao  on  rhinstrucao.rh21_instru = rhpessoal.rh01_instru";
     $sql .= "      inner join rhnacionalidade  on  rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion";
     //$sql .= "      inner join rhfuncao  on  rhfuncao.rh37_funcao = rhpessoal.rh01_funcao";     $sql2 = "";
     $sql2 = "";
     if($dbwhere==""){
       if($ed112_i_codigo!=null ){
         $sql2 .= " where progmatricula.ed112_i_codigo = $ed112_i_codigo "; 
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
   function sql_query_file ( $ed112_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from progmatricula ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed112_i_codigo!=null ){
         $sql2 .= " where progmatricula.ed112_i_codigo = $ed112_i_codigo "; 
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