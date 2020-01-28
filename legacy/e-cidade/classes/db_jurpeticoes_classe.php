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

//MODULO: juridico
//CLASSE DA ENTIDADE jurpeticoes
class cl_jurpeticoes { 
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
   var $v60_peticao = 0; 
   var $v60_inicial = 0; 
   var $v60_tipopet = 0; 
   var $v60_data_dia = null; 
   var $v60_data_mes = null; 
   var $v60_data_ano = null; 
   var $v60_data = null; 
   var $v60_hora = null; 
   var $v60_usuario = 0; 
   var $v60_texto = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 v60_peticao = int8 = Petição 
                 v60_inicial = int4 = Inicial Numero 
                 v60_tipopet = int4 = Código do Tipo de Petição 
                 v60_data = date = Data 
                 v60_hora = varchar(5) = Hora 
                 v60_usuario = int4 = Cod. Usuário 
                 v60_texto = oid = Texto da Petição 
                 ";
   //funcao construtor da classe 
   function cl_jurpeticoes() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("jurpeticoes"); 
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
       $this->v60_peticao = ($this->v60_peticao == ""?@$GLOBALS["HTTP_POST_VARS"]["v60_peticao"]:$this->v60_peticao);
       $this->v60_inicial = ($this->v60_inicial == ""?@$GLOBALS["HTTP_POST_VARS"]["v60_inicial"]:$this->v60_inicial);
       $this->v60_tipopet = ($this->v60_tipopet == ""?@$GLOBALS["HTTP_POST_VARS"]["v60_tipopet"]:$this->v60_tipopet);
       if($this->v60_data == ""){
         $this->v60_data_dia = ($this->v60_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v60_data_dia"]:$this->v60_data_dia);
         $this->v60_data_mes = ($this->v60_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v60_data_mes"]:$this->v60_data_mes);
         $this->v60_data_ano = ($this->v60_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v60_data_ano"]:$this->v60_data_ano);
         if($this->v60_data_dia != ""){
            $this->v60_data = $this->v60_data_ano."-".$this->v60_data_mes."-".$this->v60_data_dia;
         }
       }
       $this->v60_hora = ($this->v60_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["v60_hora"]:$this->v60_hora);
       $this->v60_usuario = ($this->v60_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["v60_usuario"]:$this->v60_usuario);
       $this->v60_texto = ($this->v60_texto == ""?@$GLOBALS["HTTP_POST_VARS"]["v60_texto"]:$this->v60_texto);
     }else{
       $this->v60_peticao = ($this->v60_peticao == ""?@$GLOBALS["HTTP_POST_VARS"]["v60_peticao"]:$this->v60_peticao);
     }
   }
   // funcao para inclusao
   function incluir ($v60_peticao){ 
      $this->atualizacampos();
     if($this->v60_inicial == null ){ 
       $this->erro_sql = " Campo Inicial Numero nao Informado.";
       $this->erro_campo = "v60_inicial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v60_tipopet == null ){ 
       $this->erro_sql = " Campo Código do Tipo de Petição nao Informado.";
       $this->erro_campo = "v60_tipopet";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v60_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "v60_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v60_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "v60_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v60_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "v60_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($v60_peticao == "" || $v60_peticao == null ){
       $result = db_query("select nextval('jurpeticoes_v60_peticao_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: jurpeticoes_v60_peticao_seq do campo: v60_peticao"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->v60_peticao = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from jurpeticoes_v60_peticao_seq");
       if(($result != false) && (pg_result($result,0,0) < $v60_peticao)){
         $this->erro_sql = " Campo v60_peticao maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->v60_peticao = $v60_peticao; 
       }
     }
     if(($this->v60_peticao == null) || ($this->v60_peticao == "") ){ 
       $this->erro_sql = " Campo v60_peticao nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into jurpeticoes(
                                       v60_peticao 
                                      ,v60_inicial 
                                      ,v60_tipopet 
                                      ,v60_data 
                                      ,v60_hora 
                                      ,v60_usuario 
                                      ,v60_texto 
                       )
                values (
                                $this->v60_peticao 
                               ,$this->v60_inicial 
                               ,$this->v60_tipopet 
                               ,".($this->v60_data == "null" || $this->v60_data == ""?"null":"'".$this->v60_data."'")." 
                               ,'$this->v60_hora' 
                               ,$this->v60_usuario 
                               ,$this->v60_texto 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Petições Emitidas ($this->v60_peticao) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Petições Emitidas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Petições Emitidas ($this->v60_peticao) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v60_peticao;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->v60_peticao));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6812,'$this->v60_peticao','I')");
       $resac = db_query("insert into db_acount values($acount,1116,6812,'','".AddSlashes(pg_result($resaco,0,'v60_peticao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1116,6813,'','".AddSlashes(pg_result($resaco,0,'v60_inicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1116,6814,'','".AddSlashes(pg_result($resaco,0,'v60_tipopet'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1116,6815,'','".AddSlashes(pg_result($resaco,0,'v60_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1116,6816,'','".AddSlashes(pg_result($resaco,0,'v60_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1116,6817,'','".AddSlashes(pg_result($resaco,0,'v60_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1116,6818,'','".AddSlashes(pg_result($resaco,0,'v60_texto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($v60_peticao=null) { 
      $this->atualizacampos();
     $sql = " update jurpeticoes set ";
     $virgula = "";
     if(trim($this->v60_peticao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v60_peticao"])){ 
       $sql  .= $virgula." v60_peticao = $this->v60_peticao ";
       $virgula = ",";
       if(trim($this->v60_peticao) == null ){ 
         $this->erro_sql = " Campo Petição nao Informado.";
         $this->erro_campo = "v60_peticao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v60_inicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v60_inicial"])){ 
       $sql  .= $virgula." v60_inicial = $this->v60_inicial ";
       $virgula = ",";
       if(trim($this->v60_inicial) == null ){ 
         $this->erro_sql = " Campo Inicial Numero nao Informado.";
         $this->erro_campo = "v60_inicial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v60_tipopet)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v60_tipopet"])){ 
       $sql  .= $virgula." v60_tipopet = $this->v60_tipopet ";
       $virgula = ",";
       if(trim($this->v60_tipopet) == null ){ 
         $this->erro_sql = " Campo Código do Tipo de Petição nao Informado.";
         $this->erro_campo = "v60_tipopet";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v60_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v60_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v60_data_dia"] !="") ){ 
       $sql  .= $virgula." v60_data = '$this->v60_data' ";
       $virgula = ",";
       if(trim($this->v60_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "v60_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["v60_data_dia"])){ 
         $sql  .= $virgula." v60_data = null ";
         $virgula = ",";
         if(trim($this->v60_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "v60_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->v60_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v60_hora"])){ 
       $sql  .= $virgula." v60_hora = '$this->v60_hora' ";
       $virgula = ",";
       if(trim($this->v60_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "v60_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v60_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v60_usuario"])){ 
       $sql  .= $virgula." v60_usuario = $this->v60_usuario ";
       $virgula = ",";
       if(trim($this->v60_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "v60_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v60_texto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v60_texto"])){ 
       $sql  .= $virgula." v60_texto = $this->v60_texto ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($v60_peticao!=null){
       $sql .= " v60_peticao = $this->v60_peticao";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->v60_peticao));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6812,'$this->v60_peticao','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v60_peticao"]))
           $resac = db_query("insert into db_acount values($acount,1116,6812,'".AddSlashes(pg_result($resaco,$conresaco,'v60_peticao'))."','$this->v60_peticao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v60_inicial"]))
           $resac = db_query("insert into db_acount values($acount,1116,6813,'".AddSlashes(pg_result($resaco,$conresaco,'v60_inicial'))."','$this->v60_inicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v60_tipopet"]))
           $resac = db_query("insert into db_acount values($acount,1116,6814,'".AddSlashes(pg_result($resaco,$conresaco,'v60_tipopet'))."','$this->v60_tipopet',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v60_data"]))
           $resac = db_query("insert into db_acount values($acount,1116,6815,'".AddSlashes(pg_result($resaco,$conresaco,'v60_data'))."','$this->v60_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v60_hora"]))
           $resac = db_query("insert into db_acount values($acount,1116,6816,'".AddSlashes(pg_result($resaco,$conresaco,'v60_hora'))."','$this->v60_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v60_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1116,6817,'".AddSlashes(pg_result($resaco,$conresaco,'v60_usuario'))."','$this->v60_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v60_texto"]))
           $resac = db_query("insert into db_acount values($acount,1116,6818,'".AddSlashes(pg_result($resaco,$conresaco,'v60_texto'))."','$this->v60_texto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Petições Emitidas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v60_peticao;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Petições Emitidas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v60_peticao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v60_peticao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($v60_peticao=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($v60_peticao));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6812,'$v60_peticao','E')");
         $resac = db_query("insert into db_acount values($acount,1116,6812,'','".AddSlashes(pg_result($resaco,$iresaco,'v60_peticao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1116,6813,'','".AddSlashes(pg_result($resaco,$iresaco,'v60_inicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1116,6814,'','".AddSlashes(pg_result($resaco,$iresaco,'v60_tipopet'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1116,6815,'','".AddSlashes(pg_result($resaco,$iresaco,'v60_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1116,6816,'','".AddSlashes(pg_result($resaco,$iresaco,'v60_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1116,6817,'','".AddSlashes(pg_result($resaco,$iresaco,'v60_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1116,6818,'','".AddSlashes(pg_result($resaco,$iresaco,'v60_texto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from jurpeticoes
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($v60_peticao != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v60_peticao = $v60_peticao ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Petições Emitidas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v60_peticao;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Petições Emitidas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v60_peticao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v60_peticao;
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
        $this->erro_sql   = "Record Vazio na Tabela:jurpeticoes";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $v60_peticao=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from jurpeticoes ";
     $sql .= "      inner join inicial  on  inicial.v50_inicial = jurpeticoes.v60_inicial";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = jurpeticoes.v60_usuario";
     $sql .= "      inner join jurtipopet  on  jurtipopet.v59_codpet = jurpeticoes.v60_tipopet";
     $sql .= "      inner join advog  on  advog.v57_numcgm = inicial.v50_advog";
     $sql .= "      inner join db_usuarios  as a on   a.id_usuario = inicial.v50_id_login";
     $sql .= "      inner join localiza  on  localiza.v54_codlocal = inicial.v50_codlocal";
     $sql2 = "";
     if($dbwhere==""){
       if($v60_peticao!=null ){
         $sql2 .= " where jurpeticoes.v60_peticao = $v60_peticao "; 
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
   function sql_query_file ( $v60_peticao=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from jurpeticoes ";
     $sql2 = "";
     if($dbwhere==""){
       if($v60_peticao!=null ){
         $sql2 .= " where jurpeticoes.v60_peticao = $v60_peticao "; 
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