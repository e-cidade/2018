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

//MODULO: caixa
//CLASSE DA ENTIDADE prescricao
class cl_prescricao { 
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
   var $k31_codigo = 0; 
   var $k31_data_dia = null; 
   var $k31_data_mes = null; 
   var $k31_data_ano = null; 
   var $k31_data = null; 
   var $k31_hora = null; 
   var $k31_usuario = 0; 
   var $k31_obs = null; 
   var $k31_instit = 0; 
   var $k31_situacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k31_codigo = int4 = Código da prescricao 
                 k31_data = date = Data do lancamento 
                 k31_hora = varchar(5) = Hora do lancamento 
                 k31_usuario = int4 = Cod. Usuário 
                 k31_obs = text = Observações 
                 k31_instit = int4 = Cod. Instituição 
                 k31_situacao = int4 = Situação 
                 ";
   //funcao construtor da classe 
   function cl_prescricao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("prescricao"); 
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
       $this->k31_codigo = ($this->k31_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["k31_codigo"]:$this->k31_codigo);
       if($this->k31_data == ""){
         $this->k31_data_dia = ($this->k31_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k31_data_dia"]:$this->k31_data_dia);
         $this->k31_data_mes = ($this->k31_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k31_data_mes"]:$this->k31_data_mes);
         $this->k31_data_ano = ($this->k31_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k31_data_ano"]:$this->k31_data_ano);
         if($this->k31_data_dia != ""){
            $this->k31_data = $this->k31_data_ano."-".$this->k31_data_mes."-".$this->k31_data_dia;
         }
       }
       $this->k31_hora = ($this->k31_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["k31_hora"]:$this->k31_hora);
       $this->k31_usuario = ($this->k31_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["k31_usuario"]:$this->k31_usuario);
       $this->k31_obs = ($this->k31_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["k31_obs"]:$this->k31_obs);
       $this->k31_instit = ($this->k31_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["k31_instit"]:$this->k31_instit);
       $this->k31_situacao = ($this->k31_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["k31_situacao"]:$this->k31_situacao);
     }else{
       $this->k31_codigo = ($this->k31_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["k31_codigo"]:$this->k31_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($k31_codigo){ 
      $this->atualizacampos();
     if($this->k31_data == null ){ 
       $this->erro_sql = " Campo Data do lancamento nao Informado.";
       $this->erro_campo = "k31_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k31_hora == null ){ 
       $this->erro_sql = " Campo Hora do lancamento nao Informado.";
       $this->erro_campo = "k31_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k31_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "k31_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k31_obs == null ){ 
       $this->erro_sql = " Campo Observações nao Informado.";
       $this->erro_campo = "k31_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k31_instit == null ){ 
       $this->erro_sql = " Campo Cod. Instituição nao Informado.";
       $this->erro_campo = "k31_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k31_situacao == null ){ 
       $this->erro_sql = " Campo Situação nao Informado.";
       $this->erro_campo = "k31_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k31_codigo == "" || $k31_codigo == null ){
       $result = db_query("select nextval('prescricao_k31_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: prescricao_k31_codigo_seq do campo: k31_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k31_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from prescricao_k31_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $k31_codigo)){
         $this->erro_sql = " Campo k31_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k31_codigo = $k31_codigo; 
       }
     }
     if(($this->k31_codigo == null) || ($this->k31_codigo == "") ){ 
       $this->erro_sql = " Campo k31_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into prescricao(
                                       k31_codigo 
                                      ,k31_data 
                                      ,k31_hora 
                                      ,k31_usuario 
                                      ,k31_obs 
                                      ,k31_instit 
                                      ,k31_situacao 
                       )
                values (
                                $this->k31_codigo 
                               ,".($this->k31_data == "null" || $this->k31_data == ""?"null":"'".$this->k31_data."'")." 
                               ,'$this->k31_hora' 
                               ,$this->k31_usuario 
                               ,'$this->k31_obs' 
                               ,$this->k31_instit 
                               ,$this->k31_situacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Prescricao de débitos ($this->k31_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Prescricao de débitos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Prescricao de débitos ($this->k31_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k31_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k31_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7449,'$this->k31_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1237,7449,'','".AddSlashes(pg_result($resaco,0,'k31_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1237,7450,'','".AddSlashes(pg_result($resaco,0,'k31_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1237,7451,'','".AddSlashes(pg_result($resaco,0,'k31_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1237,7452,'','".AddSlashes(pg_result($resaco,0,'k31_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1237,7582,'','".AddSlashes(pg_result($resaco,0,'k31_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1237,10670,'','".AddSlashes(pg_result($resaco,0,'k31_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1237,17633,'','".AddSlashes(pg_result($resaco,0,'k31_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k31_codigo=null) { 
      $this->atualizacampos();
     $sql = " update prescricao set ";
     $virgula = "";
     if(trim($this->k31_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k31_codigo"])){ 
       $sql  .= $virgula." k31_codigo = $this->k31_codigo ";
       $virgula = ",";
       if(trim($this->k31_codigo) == null ){ 
         $this->erro_sql = " Campo Código da prescricao nao Informado.";
         $this->erro_campo = "k31_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k31_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k31_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k31_data_dia"] !="") ){ 
       $sql  .= $virgula." k31_data = '$this->k31_data' ";
       $virgula = ",";
       if(trim($this->k31_data) == null ){ 
         $this->erro_sql = " Campo Data do lancamento nao Informado.";
         $this->erro_campo = "k31_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k31_data_dia"])){ 
         $sql  .= $virgula." k31_data = null ";
         $virgula = ",";
         if(trim($this->k31_data) == null ){ 
           $this->erro_sql = " Campo Data do lancamento nao Informado.";
           $this->erro_campo = "k31_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k31_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k31_hora"])){ 
       $sql  .= $virgula." k31_hora = '$this->k31_hora' ";
       $virgula = ",";
       if(trim($this->k31_hora) == null ){ 
         $this->erro_sql = " Campo Hora do lancamento nao Informado.";
         $this->erro_campo = "k31_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k31_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k31_usuario"])){ 
       $sql  .= $virgula." k31_usuario = $this->k31_usuario ";
       $virgula = ",";
       if(trim($this->k31_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "k31_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k31_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k31_obs"])){ 
       $sql  .= $virgula." k31_obs = '$this->k31_obs' ";
       $virgula = ",";
       if(trim($this->k31_obs) == null ){ 
         $this->erro_sql = " Campo Observações nao Informado.";
         $this->erro_campo = "k31_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k31_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k31_instit"])){ 
       $sql  .= $virgula." k31_instit = $this->k31_instit ";
       $virgula = ",";
       if(trim($this->k31_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "k31_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k31_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k31_situacao"])){ 
       $sql  .= $virgula." k31_situacao = $this->k31_situacao ";
       $virgula = ",";
       if(trim($this->k31_situacao) == null ){ 
         $this->erro_sql = " Campo Situação nao Informado.";
         $this->erro_campo = "k31_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k31_codigo!=null){
       $sql .= " k31_codigo = $this->k31_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k31_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7449,'$this->k31_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k31_codigo"]) || $this->k31_codigo != "")
           $resac = db_query("insert into db_acount values($acount,1237,7449,'".AddSlashes(pg_result($resaco,$conresaco,'k31_codigo'))."','$this->k31_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k31_data"]) || $this->k31_data != "")
           $resac = db_query("insert into db_acount values($acount,1237,7450,'".AddSlashes(pg_result($resaco,$conresaco,'k31_data'))."','$this->k31_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k31_hora"]) || $this->k31_hora != "")
           $resac = db_query("insert into db_acount values($acount,1237,7451,'".AddSlashes(pg_result($resaco,$conresaco,'k31_hora'))."','$this->k31_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k31_usuario"]) || $this->k31_usuario != "")
           $resac = db_query("insert into db_acount values($acount,1237,7452,'".AddSlashes(pg_result($resaco,$conresaco,'k31_usuario'))."','$this->k31_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k31_obs"]) || $this->k31_obs != "")
           $resac = db_query("insert into db_acount values($acount,1237,7582,'".AddSlashes(pg_result($resaco,$conresaco,'k31_obs'))."','$this->k31_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k31_instit"]) || $this->k31_instit != "")
           $resac = db_query("insert into db_acount values($acount,1237,10670,'".AddSlashes(pg_result($resaco,$conresaco,'k31_instit'))."','$this->k31_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k31_situacao"]) || $this->k31_situacao != "")
           $resac = db_query("insert into db_acount values($acount,1237,17633,'".AddSlashes(pg_result($resaco,$conresaco,'k31_situacao'))."','$this->k31_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Prescricao de débitos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k31_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Prescricao de débitos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k31_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k31_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k31_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k31_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7449,'$k31_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1237,7449,'','".AddSlashes(pg_result($resaco,$iresaco,'k31_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1237,7450,'','".AddSlashes(pg_result($resaco,$iresaco,'k31_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1237,7451,'','".AddSlashes(pg_result($resaco,$iresaco,'k31_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1237,7452,'','".AddSlashes(pg_result($resaco,$iresaco,'k31_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1237,7582,'','".AddSlashes(pg_result($resaco,$iresaco,'k31_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1237,10670,'','".AddSlashes(pg_result($resaco,$iresaco,'k31_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1237,17633,'','".AddSlashes(pg_result($resaco,$iresaco,'k31_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from prescricao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k31_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k31_codigo = $k31_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Prescricao de débitos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k31_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Prescricao de débitos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k31_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k31_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:prescricao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k31_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from prescricao ";
     $sql .= "      inner join db_config  on  db_config.codigo = prescricao.k31_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = prescricao.k31_usuario";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
    $sql2 = "";
     if($dbwhere==""){
       if($k31_codigo!=null ){
         $sql2 .= " where prescricao.k31_codigo = $k31_codigo "; 
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
   function sql_query_file ( $k31_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from prescricao ";
     $sql2 = "";
     if($dbwhere==""){
       if($k31_codigo!=null ){
         $sql2 .= " where prescricao.k31_codigo = $k31_codigo "; 
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