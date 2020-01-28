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

//MODULO: Agua
//CLASSE DA ENTIDADE aguacoletorexportasituacao
class cl_aguacoletorexportasituacao { 
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
   var $x48_sequencial = 0; 
   var $x48_aguacoletorexporta = 0; 
   var $x48_usuario = 0; 
   var $x48_data_dia = null; 
   var $x48_data_mes = null; 
   var $x48_data_ano = null; 
   var $x48_data = null; 
   var $x48_hora = null; 
   var $x48_motivo = null; 
   var $x48_situacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 x48_sequencial = int4 = Código Situação 
                 x48_aguacoletorexporta = int4 = Código Exportação 
                 x48_usuario = int4 = Cod. Usuário 
                 x48_data = date = Data Situação 
                 x48_hora = char(5) = Hora Situação 
                 x48_motivo = text = Motivo 
                 x48_situacao = int4 = Situação da Exportação 
                 ";
   //funcao construtor da classe 
   function cl_aguacoletorexportasituacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aguacoletorexportasituacao"); 
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
       $this->x48_sequencial = ($this->x48_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["x48_sequencial"]:$this->x48_sequencial);
       $this->x48_aguacoletorexporta = ($this->x48_aguacoletorexporta == ""?@$GLOBALS["HTTP_POST_VARS"]["x48_aguacoletorexporta"]:$this->x48_aguacoletorexporta);
       $this->x48_usuario = ($this->x48_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["x48_usuario"]:$this->x48_usuario);
       if($this->x48_data == ""){
         $this->x48_data_dia = ($this->x48_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["x48_data_dia"]:$this->x48_data_dia);
         $this->x48_data_mes = ($this->x48_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["x48_data_mes"]:$this->x48_data_mes);
         $this->x48_data_ano = ($this->x48_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["x48_data_ano"]:$this->x48_data_ano);
         if($this->x48_data_dia != ""){
            $this->x48_data = $this->x48_data_ano."-".$this->x48_data_mes."-".$this->x48_data_dia;
         }
       }
       $this->x48_hora = ($this->x48_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["x48_hora"]:$this->x48_hora);
       $this->x48_motivo = ($this->x48_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["x48_motivo"]:$this->x48_motivo);
       $this->x48_situacao = ($this->x48_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["x48_situacao"]:$this->x48_situacao);
     }else{
       $this->x48_sequencial = ($this->x48_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["x48_sequencial"]:$this->x48_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($x48_sequencial){ 
      $this->atualizacampos();
     if($this->x48_aguacoletorexporta == null ){ 
       $this->erro_sql = " Campo Código Exportação nao Informado.";
       $this->erro_campo = "x48_aguacoletorexporta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x48_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "x48_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x48_data == null ){ 
       $this->erro_sql = " Campo Data Situação nao Informado.";
       $this->erro_campo = "x48_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x48_hora == null ){ 
       $this->erro_sql = " Campo Hora Situação nao Informado.";
       $this->erro_campo = "x48_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x48_motivo == null ){ 
       $this->erro_sql = " Campo Motivo nao Informado.";
       $this->erro_campo = "x48_motivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x48_situacao == null ){ 
       $this->erro_sql = " Campo Situação da Exportação nao Informado.";
       $this->erro_campo = "x48_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($x48_sequencial == "" || $x48_sequencial == null ){
       $result = db_query("select nextval('aguacoletorexportasituacao_x48_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: aguacoletorexportasituacao_x48_sequencial_seq do campo: x48_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->x48_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from aguacoletorexportasituacao_x48_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $x48_sequencial)){
         $this->erro_sql = " Campo x48_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->x48_sequencial = $x48_sequencial; 
       }
     }
     if(($this->x48_sequencial == null) || ($this->x48_sequencial == "") ){ 
       $this->erro_sql = " Campo x48_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aguacoletorexportasituacao(
                                       x48_sequencial 
                                      ,x48_aguacoletorexporta 
                                      ,x48_usuario 
                                      ,x48_data 
                                      ,x48_hora 
                                      ,x48_motivo 
                                      ,x48_situacao 
                       )
                values (
                                $this->x48_sequencial 
                               ,$this->x48_aguacoletorexporta 
                               ,$this->x48_usuario 
                               ,".($this->x48_data == "null" || $this->x48_data == ""?"null":"'".$this->x48_data."'")." 
                               ,'$this->x48_hora' 
                               ,'$this->x48_motivo' 
                               ,$this->x48_situacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Agua Coletor Exporta Situação ($this->x48_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Agua Coletor Exporta Situação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Agua Coletor Exporta Situação ($this->x48_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x48_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->x48_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15352,'$this->x48_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2702,15352,'','".AddSlashes(pg_result($resaco,0,'x48_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2702,15354,'','".AddSlashes(pg_result($resaco,0,'x48_aguacoletorexporta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2702,15355,'','".AddSlashes(pg_result($resaco,0,'x48_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2702,15356,'','".AddSlashes(pg_result($resaco,0,'x48_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2702,15359,'','".AddSlashes(pg_result($resaco,0,'x48_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2702,15357,'','".AddSlashes(pg_result($resaco,0,'x48_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2702,15358,'','".AddSlashes(pg_result($resaco,0,'x48_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($x48_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update aguacoletorexportasituacao set ";
     $virgula = "";
     if(trim($this->x48_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x48_sequencial"])){ 
       $sql  .= $virgula." x48_sequencial = $this->x48_sequencial ";
       $virgula = ",";
       if(trim($this->x48_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Situação nao Informado.";
         $this->erro_campo = "x48_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x48_aguacoletorexporta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x48_aguacoletorexporta"])){ 
       $sql  .= $virgula." x48_aguacoletorexporta = $this->x48_aguacoletorexporta ";
       $virgula = ",";
       if(trim($this->x48_aguacoletorexporta) == null ){ 
         $this->erro_sql = " Campo Código Exportação nao Informado.";
         $this->erro_campo = "x48_aguacoletorexporta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x48_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x48_usuario"])){ 
       $sql  .= $virgula." x48_usuario = $this->x48_usuario ";
       $virgula = ",";
       if(trim($this->x48_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "x48_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x48_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x48_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["x48_data_dia"] !="") ){ 
       $sql  .= $virgula." x48_data = '$this->x48_data' ";
       $virgula = ",";
       if(trim($this->x48_data) == null ){ 
         $this->erro_sql = " Campo Data Situação nao Informado.";
         $this->erro_campo = "x48_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["x48_data_dia"])){ 
         $sql  .= $virgula." x48_data = null ";
         $virgula = ",";
         if(trim($this->x48_data) == null ){ 
           $this->erro_sql = " Campo Data Situação nao Informado.";
           $this->erro_campo = "x48_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->x48_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x48_hora"])){ 
       $sql  .= $virgula." x48_hora = '$this->x48_hora' ";
       $virgula = ",";
       if(trim($this->x48_hora) == null ){ 
         $this->erro_sql = " Campo Hora Situação nao Informado.";
         $this->erro_campo = "x48_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x48_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x48_motivo"])){ 
       $sql  .= $virgula." x48_motivo = '$this->x48_motivo' ";
       $virgula = ",";
       if(trim($this->x48_motivo) == null ){ 
         $this->erro_sql = " Campo Motivo nao Informado.";
         $this->erro_campo = "x48_motivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x48_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x48_situacao"])){ 
       $sql  .= $virgula." x48_situacao = $this->x48_situacao ";
       $virgula = ",";
       if(trim($this->x48_situacao) == null ){ 
         $this->erro_sql = " Campo Situação da Exportação nao Informado.";
         $this->erro_campo = "x48_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($x48_sequencial!=null){
       $sql .= " x48_sequencial = $this->x48_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->x48_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15352,'$this->x48_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x48_sequencial"]) || $this->x48_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2702,15352,'".AddSlashes(pg_result($resaco,$conresaco,'x48_sequencial'))."','$this->x48_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x48_aguacoletorexporta"]) || $this->x48_aguacoletorexporta != "")
           $resac = db_query("insert into db_acount values($acount,2702,15354,'".AddSlashes(pg_result($resaco,$conresaco,'x48_aguacoletorexporta'))."','$this->x48_aguacoletorexporta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x48_usuario"]) || $this->x48_usuario != "")
           $resac = db_query("insert into db_acount values($acount,2702,15355,'".AddSlashes(pg_result($resaco,$conresaco,'x48_usuario'))."','$this->x48_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x48_data"]) || $this->x48_data != "")
           $resac = db_query("insert into db_acount values($acount,2702,15356,'".AddSlashes(pg_result($resaco,$conresaco,'x48_data'))."','$this->x48_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x48_hora"]) || $this->x48_hora != "")
           $resac = db_query("insert into db_acount values($acount,2702,15359,'".AddSlashes(pg_result($resaco,$conresaco,'x48_hora'))."','$this->x48_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x48_motivo"]) || $this->x48_motivo != "")
           $resac = db_query("insert into db_acount values($acount,2702,15357,'".AddSlashes(pg_result($resaco,$conresaco,'x48_motivo'))."','$this->x48_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x48_situacao"]) || $this->x48_situacao != "")
           $resac = db_query("insert into db_acount values($acount,2702,15358,'".AddSlashes(pg_result($resaco,$conresaco,'x48_situacao'))."','$this->x48_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Agua Coletor Exporta Situação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->x48_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Agua Coletor Exporta Situação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->x48_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x48_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($x48_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($x48_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15352,'$x48_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2702,15352,'','".AddSlashes(pg_result($resaco,$iresaco,'x48_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2702,15354,'','".AddSlashes(pg_result($resaco,$iresaco,'x48_aguacoletorexporta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2702,15355,'','".AddSlashes(pg_result($resaco,$iresaco,'x48_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2702,15356,'','".AddSlashes(pg_result($resaco,$iresaco,'x48_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2702,15359,'','".AddSlashes(pg_result($resaco,$iresaco,'x48_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2702,15357,'','".AddSlashes(pg_result($resaco,$iresaco,'x48_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2702,15358,'','".AddSlashes(pg_result($resaco,$iresaco,'x48_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from aguacoletorexportasituacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($x48_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " x48_sequencial = $x48_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Agua Coletor Exporta Situação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$x48_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Agua Coletor Exporta Situação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$x48_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$x48_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:aguacoletorexportasituacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $x48_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguacoletorexportasituacao ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = aguacoletorexportasituacao.x48_usuario";
     $sql .= "      inner join aguacoletorexporta  on  aguacoletorexporta.x49_sequencial = aguacoletorexportasituacao.x48_aguacoletorexporta";
     $sql .= "      inner join db_config  on  db_config.codigo = aguacoletorexporta.x49_instit";
     $sql .= "      inner join aguacoletor  as a on   a.x46_sequencial = aguacoletorexporta.x49_aguacoletor";
     $sql2 = "";
     if($dbwhere==""){
       if($x48_sequencial!=null ){
         $sql2 .= " where aguacoletorexportasituacao.x48_sequencial = $x48_sequencial "; 
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
   function sql_query_file ( $x48_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguacoletorexportasituacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($x48_sequencial!=null ){
         $sql2 .= " where aguacoletorexportasituacao.x48_sequencial = $x48_sequencial "; 
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