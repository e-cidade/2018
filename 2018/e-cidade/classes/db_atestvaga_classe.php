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
//CLASSE DA ENTIDADE atestvaga
class cl_atestvaga { 
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
   var $ed102_i_codigo = 0; 
   var $ed102_i_escola = 0; 
   var $ed102_i_aluno = 0; 
   var $ed102_i_base = 0; 
   var $ed102_i_calendario = 0; 
   var $ed102_i_serie = 0; 
   var $ed102_i_turno = 0; 
   var $ed102_i_usuario = 0; 
   var $ed102_d_data_dia = null; 
   var $ed102_d_data_mes = null; 
   var $ed102_d_data_ano = null; 
   var $ed102_d_data = null; 
   var $ed102_t_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed102_i_codigo = int8 = Código Atestado 
                 ed102_i_escola = int8 = Escola 
                 ed102_i_aluno = int8 = Aluno 
                 ed102_i_base = int8 = Base Curricular 
                 ed102_i_calendario = int8 = Calendário 
                 ed102_i_serie = int8 = Série 
                 ed102_i_turno = int8 = Turno 
                 ed102_i_usuario = int8 = Usuário 
                 ed102_d_data = date = Data do Atestado 
                 ed102_t_obs = text = Observações 
                 ";
   //funcao construtor da classe 
   function cl_atestvaga() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("atestvaga"); 
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
       $this->ed102_i_codigo = ($this->ed102_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed102_i_codigo"]:$this->ed102_i_codigo);
       $this->ed102_i_escola = ($this->ed102_i_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed102_i_escola"]:$this->ed102_i_escola);
       $this->ed102_i_aluno = ($this->ed102_i_aluno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed102_i_aluno"]:$this->ed102_i_aluno);
       $this->ed102_i_base = ($this->ed102_i_base == ""?@$GLOBALS["HTTP_POST_VARS"]["ed102_i_base"]:$this->ed102_i_base);
       $this->ed102_i_calendario = ($this->ed102_i_calendario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed102_i_calendario"]:$this->ed102_i_calendario);
       $this->ed102_i_serie = ($this->ed102_i_serie == ""?@$GLOBALS["HTTP_POST_VARS"]["ed102_i_serie"]:$this->ed102_i_serie);
       $this->ed102_i_turno = ($this->ed102_i_turno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed102_i_turno"]:$this->ed102_i_turno);
       $this->ed102_i_usuario = ($this->ed102_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed102_i_usuario"]:$this->ed102_i_usuario);
       if($this->ed102_d_data == ""){
         $this->ed102_d_data_dia = ($this->ed102_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed102_d_data_dia"]:$this->ed102_d_data_dia);
         $this->ed102_d_data_mes = ($this->ed102_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed102_d_data_mes"]:$this->ed102_d_data_mes);
         $this->ed102_d_data_ano = ($this->ed102_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed102_d_data_ano"]:$this->ed102_d_data_ano);
         if($this->ed102_d_data_dia != ""){
            $this->ed102_d_data = $this->ed102_d_data_ano."-".$this->ed102_d_data_mes."-".$this->ed102_d_data_dia;
         }
       }
       $this->ed102_t_obs = ($this->ed102_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ed102_t_obs"]:$this->ed102_t_obs);
     }else{
       $this->ed102_i_codigo = ($this->ed102_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed102_i_codigo"]:$this->ed102_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed102_i_codigo){ 
      $this->atualizacampos();
     if($this->ed102_i_escola == null ){ 
       $this->erro_sql = " Campo Escola nao Informado.";
       $this->erro_campo = "ed102_i_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed102_i_aluno == null ){ 
       $this->erro_sql = " Campo Aluno nao Informado.";
       $this->erro_campo = "ed102_i_aluno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed102_i_base == null ){ 
       $this->erro_sql = " Campo Base Curricular nao Informado.";
       $this->erro_campo = "ed102_i_base";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed102_i_calendario == null ){ 
       $this->erro_sql = " Campo Calendário nao Informado.";
       $this->erro_campo = "ed102_i_calendario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed102_i_serie == null ){ 
       $this->erro_sql = " Campo Série nao Informado.";
       $this->erro_campo = "ed102_i_serie";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed102_i_turno == null ){ 
       $this->erro_sql = " Campo Turno nao Informado.";
       $this->erro_campo = "ed102_i_turno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed102_i_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "ed102_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed102_d_data == null ){ 
       $this->erro_sql = " Campo Data do Atestado nao Informado.";
       $this->erro_campo = "ed102_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed102_i_codigo == "" || $ed102_i_codigo == null ){
       $result = db_query("select nextval('atestvaga_ed102_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: atestvaga_ed102_i_codigo_seq do campo: ed102_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed102_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from atestvaga_ed102_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed102_i_codigo)){
         $this->erro_sql = " Campo ed102_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed102_i_codigo = $ed102_i_codigo; 
       }
     }
     if(($this->ed102_i_codigo == null) || ($this->ed102_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed102_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into atestvaga(
                                       ed102_i_codigo 
                                      ,ed102_i_escola 
                                      ,ed102_i_aluno 
                                      ,ed102_i_base 
                                      ,ed102_i_calendario 
                                      ,ed102_i_serie 
                                      ,ed102_i_turno 
                                      ,ed102_i_usuario 
                                      ,ed102_d_data 
                                      ,ed102_t_obs 
                       )
                values (
                                $this->ed102_i_codigo 
                               ,$this->ed102_i_escola 
                               ,$this->ed102_i_aluno 
                               ,$this->ed102_i_base 
                               ,$this->ed102_i_calendario 
                               ,$this->ed102_i_serie 
                               ,$this->ed102_i_turno 
                               ,$this->ed102_i_usuario 
                               ,".($this->ed102_d_data == "null" || $this->ed102_d_data == ""?"null":"'".$this->ed102_d_data."'")." 
                               ,'$this->ed102_t_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Atestado de Vagas ($this->ed102_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Atestado de Vagas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Atestado de Vagas ($this->ed102_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed102_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed102_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1009035,'$this->ed102_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010161,1009035,'','".AddSlashes(pg_result($resaco,0,'ed102_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010161,1009036,'','".AddSlashes(pg_result($resaco,0,'ed102_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010161,1009043,'','".AddSlashes(pg_result($resaco,0,'ed102_i_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010161,1009037,'','".AddSlashes(pg_result($resaco,0,'ed102_i_base'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010161,1009038,'','".AddSlashes(pg_result($resaco,0,'ed102_i_calendario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010161,1009039,'','".AddSlashes(pg_result($resaco,0,'ed102_i_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010161,1009040,'','".AddSlashes(pg_result($resaco,0,'ed102_i_turno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010161,1009044,'','".AddSlashes(pg_result($resaco,0,'ed102_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010161,1009041,'','".AddSlashes(pg_result($resaco,0,'ed102_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010161,1009042,'','".AddSlashes(pg_result($resaco,0,'ed102_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed102_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update atestvaga set ";
     $virgula = "";
     if(trim($this->ed102_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed102_i_codigo"])){ 
       $sql  .= $virgula." ed102_i_codigo = $this->ed102_i_codigo ";
       $virgula = ",";
       if(trim($this->ed102_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código Atestado nao Informado.";
         $this->erro_campo = "ed102_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed102_i_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed102_i_escola"])){ 
       $sql  .= $virgula." ed102_i_escola = $this->ed102_i_escola ";
       $virgula = ",";
       if(trim($this->ed102_i_escola) == null ){ 
         $this->erro_sql = " Campo Escola nao Informado.";
         $this->erro_campo = "ed102_i_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed102_i_aluno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed102_i_aluno"])){ 
       $sql  .= $virgula." ed102_i_aluno = $this->ed102_i_aluno ";
       $virgula = ",";
       if(trim($this->ed102_i_aluno) == null ){ 
         $this->erro_sql = " Campo Aluno nao Informado.";
         $this->erro_campo = "ed102_i_aluno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed102_i_base)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed102_i_base"])){ 
       $sql  .= $virgula." ed102_i_base = $this->ed102_i_base ";
       $virgula = ",";
       if(trim($this->ed102_i_base) == null ){ 
         $this->erro_sql = " Campo Base Curricular nao Informado.";
         $this->erro_campo = "ed102_i_base";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed102_i_calendario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed102_i_calendario"])){ 
       $sql  .= $virgula." ed102_i_calendario = $this->ed102_i_calendario ";
       $virgula = ",";
       if(trim($this->ed102_i_calendario) == null ){ 
         $this->erro_sql = " Campo Calendário nao Informado.";
         $this->erro_campo = "ed102_i_calendario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed102_i_serie)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed102_i_serie"])){ 
       $sql  .= $virgula." ed102_i_serie = $this->ed102_i_serie ";
       $virgula = ",";
       if(trim($this->ed102_i_serie) == null ){ 
         $this->erro_sql = " Campo Série nao Informado.";
         $this->erro_campo = "ed102_i_serie";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed102_i_turno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed102_i_turno"])){ 
       $sql  .= $virgula." ed102_i_turno = $this->ed102_i_turno ";
       $virgula = ",";
       if(trim($this->ed102_i_turno) == null ){ 
         $this->erro_sql = " Campo Turno nao Informado.";
         $this->erro_campo = "ed102_i_turno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed102_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed102_i_usuario"])){ 
       $sql  .= $virgula." ed102_i_usuario = $this->ed102_i_usuario ";
       $virgula = ",";
       if(trim($this->ed102_i_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "ed102_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed102_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed102_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed102_d_data_dia"] !="") ){ 
       $sql  .= $virgula." ed102_d_data = '$this->ed102_d_data' ";
       $virgula = ",";
       if(trim($this->ed102_d_data) == null ){ 
         $this->erro_sql = " Campo Data do Atestado nao Informado.";
         $this->erro_campo = "ed102_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed102_d_data_dia"])){ 
         $sql  .= $virgula." ed102_d_data = null ";
         $virgula = ",";
         if(trim($this->ed102_d_data) == null ){ 
           $this->erro_sql = " Campo Data do Atestado nao Informado.";
           $this->erro_campo = "ed102_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed102_t_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed102_t_obs"])){ 
       $sql  .= $virgula." ed102_t_obs = '$this->ed102_t_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed102_i_codigo!=null){
       $sql .= " ed102_i_codigo = $this->ed102_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed102_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009035,'$this->ed102_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed102_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010161,1009035,'".AddSlashes(pg_result($resaco,$conresaco,'ed102_i_codigo'))."','$this->ed102_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed102_i_escola"]))
           $resac = db_query("insert into db_acount values($acount,1010161,1009036,'".AddSlashes(pg_result($resaco,$conresaco,'ed102_i_escola'))."','$this->ed102_i_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed102_i_aluno"]))
           $resac = db_query("insert into db_acount values($acount,1010161,1009043,'".AddSlashes(pg_result($resaco,$conresaco,'ed102_i_aluno'))."','$this->ed102_i_aluno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed102_i_base"]))
           $resac = db_query("insert into db_acount values($acount,1010161,1009037,'".AddSlashes(pg_result($resaco,$conresaco,'ed102_i_base'))."','$this->ed102_i_base',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed102_i_calendario"]))
           $resac = db_query("insert into db_acount values($acount,1010161,1009038,'".AddSlashes(pg_result($resaco,$conresaco,'ed102_i_calendario'))."','$this->ed102_i_calendario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed102_i_serie"]))
           $resac = db_query("insert into db_acount values($acount,1010161,1009039,'".AddSlashes(pg_result($resaco,$conresaco,'ed102_i_serie'))."','$this->ed102_i_serie',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed102_i_turno"]))
           $resac = db_query("insert into db_acount values($acount,1010161,1009040,'".AddSlashes(pg_result($resaco,$conresaco,'ed102_i_turno'))."','$this->ed102_i_turno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed102_i_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1010161,1009044,'".AddSlashes(pg_result($resaco,$conresaco,'ed102_i_usuario'))."','$this->ed102_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed102_d_data"]))
           $resac = db_query("insert into db_acount values($acount,1010161,1009041,'".AddSlashes(pg_result($resaco,$conresaco,'ed102_d_data'))."','$this->ed102_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed102_t_obs"]))
           $resac = db_query("insert into db_acount values($acount,1010161,1009042,'".AddSlashes(pg_result($resaco,$conresaco,'ed102_t_obs'))."','$this->ed102_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Atestado de Vagas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed102_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Atestado de Vagas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed102_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed102_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed102_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed102_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009035,'$ed102_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010161,1009035,'','".AddSlashes(pg_result($resaco,$iresaco,'ed102_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010161,1009036,'','".AddSlashes(pg_result($resaco,$iresaco,'ed102_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010161,1009043,'','".AddSlashes(pg_result($resaco,$iresaco,'ed102_i_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010161,1009037,'','".AddSlashes(pg_result($resaco,$iresaco,'ed102_i_base'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010161,1009038,'','".AddSlashes(pg_result($resaco,$iresaco,'ed102_i_calendario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010161,1009039,'','".AddSlashes(pg_result($resaco,$iresaco,'ed102_i_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010161,1009040,'','".AddSlashes(pg_result($resaco,$iresaco,'ed102_i_turno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010161,1009044,'','".AddSlashes(pg_result($resaco,$iresaco,'ed102_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010161,1009041,'','".AddSlashes(pg_result($resaco,$iresaco,'ed102_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010161,1009042,'','".AddSlashes(pg_result($resaco,$iresaco,'ed102_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from atestvaga
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed102_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed102_i_codigo = $ed102_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Atestado de Vagas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed102_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Atestado de Vagas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed102_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed102_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:atestvaga";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed102_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atestvaga ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = atestvaga.ed102_i_usuario";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = atestvaga.ed102_i_escola";
     $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql .= "      inner join turno  on  turno.ed15_i_codigo = atestvaga.ed102_i_turno";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = atestvaga.ed102_i_serie";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = serie.ed11_i_ensino";
     $sql .= "      inner join base  on  base.ed31_i_codigo = atestvaga.ed102_i_base";
     $sql .= "      inner join cursoedu  on  cursoedu.ed29_i_codigo = base.ed31_i_curso";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = atestvaga.ed102_i_aluno";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = atestvaga.ed102_i_calendario";
     $sql .= "      inner join duracaocal  on  duracaocal.ed55_i_codigo = calendario.ed52_i_duracaocal";   
     $sql2 = "";
     if($dbwhere==""){
       if($ed102_i_codigo!=null ){
         $sql2 .= " where atestvaga.ed102_i_codigo = $ed102_i_codigo "; 
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
   function sql_query_file ( $ed102_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atestvaga ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed102_i_codigo!=null ){
         $sql2 .= " where atestvaga.ed102_i_codigo = $ed102_i_codigo "; 
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