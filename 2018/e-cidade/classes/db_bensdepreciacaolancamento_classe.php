<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: patrimonio
//CLASSE DA ENTIDADE bensdepreciacaolancamento
class cl_bensdepreciacaolancamento { 
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
   var $t78_sequencial = 0; 
   var $t78_usuario = 0; 
   var $t78_instit = 0; 
   var $t78_mes = 0; 
   var $t78_ano = 0; 
   var $t78_data_dia = null; 
   var $t78_data_mes = null; 
   var $t78_data_ano = null; 
   var $t78_data = null; 
   var $t78_estornado = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 t78_sequencial = int4 = Sequencial 
                 t78_usuario = int4 = Cod. Usuário 
                 t78_instit = int4 = Cod. Instituição 
                 t78_mes = int4 = Mês 
                 t78_ano = int4 = Ano 
                 t78_data = date = Data 
                 t78_estornado = bool = Estornado 
                 ";
   //funcao construtor da classe 
   function cl_bensdepreciacaolancamento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("bensdepreciacaolancamento"); 
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
       $this->t78_sequencial = ($this->t78_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["t78_sequencial"]:$this->t78_sequencial);
       $this->t78_usuario = ($this->t78_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["t78_usuario"]:$this->t78_usuario);
       $this->t78_instit = ($this->t78_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["t78_instit"]:$this->t78_instit);
       $this->t78_mes = ($this->t78_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["t78_mes"]:$this->t78_mes);
       $this->t78_ano = ($this->t78_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["t78_ano"]:$this->t78_ano);
       if($this->t78_data == ""){
         $this->t78_data_dia = ($this->t78_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["t78_data_dia"]:$this->t78_data_dia);
         $this->t78_data_mes = ($this->t78_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["t78_data_mes"]:$this->t78_data_mes);
         $this->t78_data_ano = ($this->t78_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["t78_data_ano"]:$this->t78_data_ano);
         if($this->t78_data_dia != ""){
            $this->t78_data = $this->t78_data_ano."-".$this->t78_data_mes."-".$this->t78_data_dia;
         }
       }
       $this->t78_estornado = ($this->t78_estornado == "f"?@$GLOBALS["HTTP_POST_VARS"]["t78_estornado"]:$this->t78_estornado);
     }else{
       $this->t78_sequencial = ($this->t78_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["t78_sequencial"]:$this->t78_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($t78_sequencial){ 
      $this->atualizacampos();
     if($this->t78_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "t78_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t78_instit == null ){ 
       $this->erro_sql = " Campo Cod. Instituição nao Informado.";
       $this->erro_campo = "t78_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t78_mes == null ){ 
       $this->erro_sql = " Campo Mês nao Informado.";
       $this->erro_campo = "t78_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t78_ano == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "t78_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t78_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "t78_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t78_estornado == null ){ 
       $this->erro_sql = " Campo Estornado nao Informado.";
       $this->erro_campo = "t78_estornado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($t78_sequencial == "" || $t78_sequencial == null ){
       $result = db_query("select nextval('bensdepreciacaolancamento_c104_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: bensdepreciacaolancamento_c104_sequencial_seq do campo: t78_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->t78_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from bensdepreciacaolancamento_c104_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $t78_sequencial)){
         $this->erro_sql = " Campo t78_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->t78_sequencial = $t78_sequencial; 
       }
     }
     if(($this->t78_sequencial == null) || ($this->t78_sequencial == "") ){ 
       $this->erro_sql = " Campo t78_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into bensdepreciacaolancamento(
                                       t78_sequencial 
                                      ,t78_usuario 
                                      ,t78_instit 
                                      ,t78_mes 
                                      ,t78_ano 
                                      ,t78_data 
                                      ,t78_estornado 
                       )
                values (
                                $this->t78_sequencial 
                               ,$this->t78_usuario 
                               ,$this->t78_instit 
                               ,$this->t78_mes 
                               ,$this->t78_ano 
                               ,".($this->t78_data == "null" || $this->t78_data == ""?"null":"'".$this->t78_data."'")." 
                               ,'$this->t78_estornado' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Lançamento da depreciação de bens ($this->t78_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Lançamento da depreciação de bens já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Lançamento da depreciação de bens ($this->t78_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t78_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->t78_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19477,'$this->t78_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3458,19477,'','".AddSlashes(pg_result($resaco,0,'t78_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3458,19489,'','".AddSlashes(pg_result($resaco,0,'t78_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3458,19482,'','".AddSlashes(pg_result($resaco,0,'t78_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3458,19478,'','".AddSlashes(pg_result($resaco,0,'t78_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3458,19479,'','".AddSlashes(pg_result($resaco,0,'t78_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3458,19480,'','".AddSlashes(pg_result($resaco,0,'t78_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3458,19481,'','".AddSlashes(pg_result($resaco,0,'t78_estornado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($t78_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update bensdepreciacaolancamento set ";
     $virgula = "";
     if(trim($this->t78_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t78_sequencial"])){ 
       $sql  .= $virgula." t78_sequencial = $this->t78_sequencial ";
       $virgula = ",";
       if(trim($this->t78_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "t78_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t78_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t78_usuario"])){ 
       $sql  .= $virgula." t78_usuario = $this->t78_usuario ";
       $virgula = ",";
       if(trim($this->t78_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "t78_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t78_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t78_instit"])){ 
       $sql  .= $virgula." t78_instit = $this->t78_instit ";
       $virgula = ",";
       if(trim($this->t78_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "t78_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t78_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t78_mes"])){ 
       $sql  .= $virgula." t78_mes = $this->t78_mes ";
       $virgula = ",";
       if(trim($this->t78_mes) == null ){ 
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "t78_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t78_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t78_ano"])){ 
       $sql  .= $virgula." t78_ano = $this->t78_ano ";
       $virgula = ",";
       if(trim($this->t78_ano) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "t78_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t78_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t78_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["t78_data_dia"] !="") ){ 
       $sql  .= $virgula." t78_data = '$this->t78_data' ";
       $virgula = ",";
       if(trim($this->t78_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "t78_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["t78_data_dia"])){ 
         $sql  .= $virgula." t78_data = null ";
         $virgula = ",";
         if(trim($this->t78_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "t78_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->t78_estornado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t78_estornado"])){ 
       $sql  .= $virgula." t78_estornado = '$this->t78_estornado' ";
       $virgula = ",";
       if(trim($this->t78_estornado) == null ){ 
         $this->erro_sql = " Campo Estornado nao Informado.";
         $this->erro_campo = "t78_estornado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($t78_sequencial!=null){
       $sql .= " t78_sequencial = $this->t78_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->t78_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19477,'$this->t78_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t78_sequencial"]) || $this->t78_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3458,19477,'".AddSlashes(pg_result($resaco,$conresaco,'t78_sequencial'))."','$this->t78_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t78_usuario"]) || $this->t78_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3458,19489,'".AddSlashes(pg_result($resaco,$conresaco,'t78_usuario'))."','$this->t78_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t78_instit"]) || $this->t78_instit != "")
           $resac = db_query("insert into db_acount values($acount,3458,19482,'".AddSlashes(pg_result($resaco,$conresaco,'t78_instit'))."','$this->t78_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t78_mes"]) || $this->t78_mes != "")
           $resac = db_query("insert into db_acount values($acount,3458,19478,'".AddSlashes(pg_result($resaco,$conresaco,'t78_mes'))."','$this->t78_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t78_ano"]) || $this->t78_ano != "")
           $resac = db_query("insert into db_acount values($acount,3458,19479,'".AddSlashes(pg_result($resaco,$conresaco,'t78_ano'))."','$this->t78_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t78_data"]) || $this->t78_data != "")
           $resac = db_query("insert into db_acount values($acount,3458,19480,'".AddSlashes(pg_result($resaco,$conresaco,'t78_data'))."','$this->t78_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t78_estornado"]) || $this->t78_estornado != "")
           $resac = db_query("insert into db_acount values($acount,3458,19481,'".AddSlashes(pg_result($resaco,$conresaco,'t78_estornado'))."','$this->t78_estornado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lançamento da depreciação de bens nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t78_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lançamento da depreciação de bens nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t78_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t78_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($t78_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($t78_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19477,'$t78_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3458,19477,'','".AddSlashes(pg_result($resaco,$iresaco,'t78_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3458,19489,'','".AddSlashes(pg_result($resaco,$iresaco,'t78_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3458,19482,'','".AddSlashes(pg_result($resaco,$iresaco,'t78_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3458,19478,'','".AddSlashes(pg_result($resaco,$iresaco,'t78_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3458,19479,'','".AddSlashes(pg_result($resaco,$iresaco,'t78_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3458,19480,'','".AddSlashes(pg_result($resaco,$iresaco,'t78_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3458,19481,'','".AddSlashes(pg_result($resaco,$iresaco,'t78_estornado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from bensdepreciacaolancamento
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t78_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t78_sequencial = $t78_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lançamento da depreciação de bens nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t78_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lançamento da depreciação de bens nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t78_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t78_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:bensdepreciacaolancamento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $t78_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bensdepreciacaolancamento ";
     $sql .= "      inner join db_config  on  db_config.codigo = bensdepreciacaolancamento.t78_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = bensdepreciacaolancamento.t78_usuario";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if($dbwhere==""){
       if($t78_sequencial!=null ){
         $sql2 .= " where bensdepreciacaolancamento.t78_sequencial = $t78_sequencial "; 
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
   function sql_query_file ( $t78_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bensdepreciacaolancamento ";
     $sql2 = "";
     if($dbwhere==""){
       if($t78_sequencial!=null ){
         $sql2 .= " where bensdepreciacaolancamento.t78_sequencial = $t78_sequencial "; 
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