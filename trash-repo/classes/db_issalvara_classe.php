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

//MODULO: issqn
//CLASSE DA ENTIDADE issalvara
class cl_issalvara { 
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
   var $q123_sequencial = 0; 
   var $q123_isstipoalvara = 0; 
   var $q123_inscr = 0; 
   var $q123_dtinclusao_dia = null; 
   var $q123_dtinclusao_mes = null; 
   var $q123_dtinclusao_ano = null; 
   var $q123_dtinclusao = null; 
   var $q123_situacao = 0; 
   var $q123_usuario = 0; 
   var $q123_geradoautomatico = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q123_sequencial = int4 = Sequencial 
                 q123_isstipoalvara = int4 = Tipo de Alvará 
                 q123_inscr = int4 = Inscrição 
                 q123_dtinclusao = date = Data de Criação 
                 q123_situacao = int4 = Situação 
                 q123_usuario = int4 = Usuário 
                 q123_geradoautomatico = bool = Gerado automatico 
                 ";
   //funcao construtor da classe 
   function cl_issalvara() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("issalvara"); 
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
       $this->q123_sequencial = ($this->q123_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q123_sequencial"]:$this->q123_sequencial);
       $this->q123_isstipoalvara = ($this->q123_isstipoalvara == ""?@$GLOBALS["HTTP_POST_VARS"]["q123_isstipoalvara"]:$this->q123_isstipoalvara);
       $this->q123_inscr = ($this->q123_inscr == ""?@$GLOBALS["HTTP_POST_VARS"]["q123_inscr"]:$this->q123_inscr);
       if($this->q123_dtinclusao == ""){
         $this->q123_dtinclusao_dia = ($this->q123_dtinclusao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q123_dtinclusao_dia"]:$this->q123_dtinclusao_dia);
         $this->q123_dtinclusao_mes = ($this->q123_dtinclusao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q123_dtinclusao_mes"]:$this->q123_dtinclusao_mes);
         $this->q123_dtinclusao_ano = ($this->q123_dtinclusao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q123_dtinclusao_ano"]:$this->q123_dtinclusao_ano);
         if($this->q123_dtinclusao_dia != ""){
            $this->q123_dtinclusao = $this->q123_dtinclusao_ano."-".$this->q123_dtinclusao_mes."-".$this->q123_dtinclusao_dia;
         }
       }
       $this->q123_situacao = ($this->q123_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["q123_situacao"]:$this->q123_situacao);
       $this->q123_usuario = ($this->q123_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["q123_usuario"]:$this->q123_usuario);
       $this->q123_geradoautomatico = ($this->q123_geradoautomatico == "f"?@$GLOBALS["HTTP_POST_VARS"]["q123_geradoautomatico"]:$this->q123_geradoautomatico);
     }else{
       $this->q123_sequencial = ($this->q123_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q123_sequencial"]:$this->q123_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q123_sequencial){ 
      $this->atualizacampos();
     if($this->q123_isstipoalvara == null ){ 
       $this->erro_sql = " Campo Tipo de Alvará nao Informado.";
       $this->erro_campo = "q123_isstipoalvara";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q123_inscr == null ){ 
       $this->erro_sql = " Campo Inscrição nao Informado.";
       $this->erro_campo = "q123_inscr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q123_dtinclusao == null ){ 
       $this->erro_sql = " Campo Data de Criação nao Informado.";
       $this->erro_campo = "q123_dtinclusao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q123_situacao == null ){ 
       $this->erro_sql = " Campo Situação nao Informado.";
       $this->erro_campo = "q123_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q123_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "q123_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q123_geradoautomatico == null ){ 
       $this->q123_geradoautomatico = "f";
     }
     if($q123_sequencial == "" || $q123_sequencial == null ){
       $result = db_query("select nextval('issalvara_q123_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: issalvara_q123_sequencial_seq do campo: q123_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q123_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from issalvara_q123_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q123_sequencial)){
         $this->erro_sql = " Campo q123_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q123_sequencial = $q123_sequencial; 
       }
     }
     if(($this->q123_sequencial == null) || ($this->q123_sequencial == "") ){ 
       $this->erro_sql = " Campo q123_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into issalvara(
                                       q123_sequencial 
                                      ,q123_isstipoalvara 
                                      ,q123_inscr 
                                      ,q123_dtinclusao 
                                      ,q123_situacao 
                                      ,q123_usuario 
                                      ,q123_geradoautomatico 
                       )
                values (
                                $this->q123_sequencial 
                               ,$this->q123_isstipoalvara 
                               ,$this->q123_inscr 
                               ,".($this->q123_dtinclusao == "null" || $this->q123_dtinclusao == ""?"null":"'".$this->q123_dtinclusao."'")." 
                               ,$this->q123_situacao 
                               ,$this->q123_usuario 
                               ,'$this->q123_geradoautomatico' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Alvarás Criados ($this->q123_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Alvarás Criados já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Alvarás Criados ($this->q123_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q123_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q123_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18328,'$this->q123_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3245,18328,'','".AddSlashes(pg_result($resaco,0,'q123_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3245,18329,'','".AddSlashes(pg_result($resaco,0,'q123_isstipoalvara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3245,18330,'','".AddSlashes(pg_result($resaco,0,'q123_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3245,18331,'','".AddSlashes(pg_result($resaco,0,'q123_dtinclusao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3245,18332,'','".AddSlashes(pg_result($resaco,0,'q123_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3245,18333,'','".AddSlashes(pg_result($resaco,0,'q123_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3245,18373,'','".AddSlashes(pg_result($resaco,0,'q123_geradoautomatico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q123_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update issalvara set ";
     $virgula = "";
     if(trim($this->q123_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q123_sequencial"])){ 
       $sql  .= $virgula." q123_sequencial = $this->q123_sequencial ";
       $virgula = ",";
       if(trim($this->q123_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "q123_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q123_isstipoalvara)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q123_isstipoalvara"])){ 
       $sql  .= $virgula." q123_isstipoalvara = $this->q123_isstipoalvara ";
       $virgula = ",";
       if(trim($this->q123_isstipoalvara) == null ){ 
         $this->erro_sql = " Campo Tipo de Alvará nao Informado.";
         $this->erro_campo = "q123_isstipoalvara";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q123_inscr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q123_inscr"])){ 
       $sql  .= $virgula." q123_inscr = $this->q123_inscr ";
       $virgula = ",";
       if(trim($this->q123_inscr) == null ){ 
         $this->erro_sql = " Campo Inscrição nao Informado.";
         $this->erro_campo = "q123_inscr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q123_dtinclusao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q123_dtinclusao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q123_dtinclusao_dia"] !="") ){ 
       $sql  .= $virgula." q123_dtinclusao = '$this->q123_dtinclusao' ";
       $virgula = ",";
       if(trim($this->q123_dtinclusao) == null ){ 
         $this->erro_sql = " Campo Data de Criação nao Informado.";
         $this->erro_campo = "q123_dtinclusao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q123_dtinclusao_dia"])){ 
         $sql  .= $virgula." q123_dtinclusao = null ";
         $virgula = ",";
         if(trim($this->q123_dtinclusao) == null ){ 
           $this->erro_sql = " Campo Data de Criação nao Informado.";
           $this->erro_campo = "q123_dtinclusao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->q123_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q123_situacao"])){ 
       $sql  .= $virgula." q123_situacao = $this->q123_situacao ";
       $virgula = ",";
       if(trim($this->q123_situacao) == null ){ 
         $this->erro_sql = " Campo Situação nao Informado.";
         $this->erro_campo = "q123_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q123_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q123_usuario"])){ 
       $sql  .= $virgula." q123_usuario = $this->q123_usuario ";
       $virgula = ",";
       if(trim($this->q123_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "q123_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q123_geradoautomatico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q123_geradoautomatico"])){ 
       $sql  .= $virgula." q123_geradoautomatico = '$this->q123_geradoautomatico' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($q123_sequencial!=null){
       $sql .= " q123_sequencial = $this->q123_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q123_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18328,'$this->q123_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q123_sequencial"]) || $this->q123_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3245,18328,'".AddSlashes(pg_result($resaco,$conresaco,'q123_sequencial'))."','$this->q123_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q123_isstipoalvara"]) || $this->q123_isstipoalvara != "")
           $resac = db_query("insert into db_acount values($acount,3245,18329,'".AddSlashes(pg_result($resaco,$conresaco,'q123_isstipoalvara'))."','$this->q123_isstipoalvara',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q123_inscr"]) || $this->q123_inscr != "")
           $resac = db_query("insert into db_acount values($acount,3245,18330,'".AddSlashes(pg_result($resaco,$conresaco,'q123_inscr'))."','$this->q123_inscr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q123_dtinclusao"]) || $this->q123_dtinclusao != "")
           $resac = db_query("insert into db_acount values($acount,3245,18331,'".AddSlashes(pg_result($resaco,$conresaco,'q123_dtinclusao'))."','$this->q123_dtinclusao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q123_situacao"]) || $this->q123_situacao != "")
           $resac = db_query("insert into db_acount values($acount,3245,18332,'".AddSlashes(pg_result($resaco,$conresaco,'q123_situacao'))."','$this->q123_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q123_usuario"]) || $this->q123_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3245,18333,'".AddSlashes(pg_result($resaco,$conresaco,'q123_usuario'))."','$this->q123_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q123_geradoautomatico"]) || $this->q123_geradoautomatico != "")
           $resac = db_query("insert into db_acount values($acount,3245,18373,'".AddSlashes(pg_result($resaco,$conresaco,'q123_geradoautomatico'))."','$this->q123_geradoautomatico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Alvarás Criados nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q123_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Alvarás Criados nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q123_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q123_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q123_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q123_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18328,'$q123_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3245,18328,'','".AddSlashes(pg_result($resaco,$iresaco,'q123_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3245,18329,'','".AddSlashes(pg_result($resaco,$iresaco,'q123_isstipoalvara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3245,18330,'','".AddSlashes(pg_result($resaco,$iresaco,'q123_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3245,18331,'','".AddSlashes(pg_result($resaco,$iresaco,'q123_dtinclusao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3245,18332,'','".AddSlashes(pg_result($resaco,$iresaco,'q123_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3245,18333,'','".AddSlashes(pg_result($resaco,$iresaco,'q123_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3245,18373,'','".AddSlashes(pg_result($resaco,$iresaco,'q123_geradoautomatico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from issalvara
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q123_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q123_sequencial = $q123_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Alvarás Criados nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q123_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Alvarás Criados nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q123_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q123_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:issalvara";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q123_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issalvara ";
     $sql .= "      inner join issbase  on  issbase.q02_inscr = issalvara.q123_inscr";
     $sql .= "      inner join isstipoalvara  on  isstipoalvara.q98_sequencial = issalvara.q123_isstipoalvara";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = issbase.q02_numcgm";
     $sql .= "      inner join db_documentotemplate  on  db_documentotemplate.db82_sequencial = isstipoalvara.q98_documento";
     $sql .= "      inner join issgrupotipoalvara  on  issgrupotipoalvara.q97_sequencial = isstipoalvara.q98_issgrupotipoalvara";
     $sql2 = "";
     if($dbwhere==""){
       if($q123_sequencial!=null ){
         $sql2 .= " where issalvara.q123_sequencial = $q123_sequencial "; 
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
   function sql_query_file ( $q123_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issalvara ";
     $sql2 = "";
     if($dbwhere==""){
       if($q123_sequencial!=null ){
         $sql2 .= " where issalvara.q123_sequencial = $q123_sequencial "; 
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
   
   /**
    * Busca registros para liberacao do alvara
    * @param integer $q123_sequencial - Sequencial da Tabela
    * @param string  $campos          - Campos para busca do SQL
    * @param string  $ordem           - Ordem dos Registros SQL 
    * @param string  $dbwhere         - Filtro para busca do SQL
    * @param integer $iDepto          - Filtro especifico para o Departamento
    * @return string
    */
   function sql_queryAlvara ( $q123_sequencial=null,$campos="*",$ordem=null,$dbwhere="", $iDepto = null){ 
     
      $sql  = "select * from ( select  {$campos},                                                             ";
      $sql .= "                       q123_isstipoalvara as dl_tipo_alvara,                                   ";
      $sql .= "                       (select q120_isstipomovalvara                                           ";
      $sql .= "                         from issmovalvara                                                     ";
      $sql .= "                        where q120_issalvara = q123_sequencial                                 ";
      $sql .= "                        order by q120_sequencial desc                                          ";
      $sql .= "                        limit 1                                                                ";
      $sql .= "                       ) as dl_ultima_movimentacao,                                            ";
      $sql .= "                       q123_geradoautomatico as dl_automatico                                  ";
      $sql .= "                from issalvara                                                                 ";
      $sql .= "                     inner join issbase  on issbase.q02_inscr = issalvara.q123_inscr           ";
      $sql .= "                     inner join cgm      on cgm.z01_numcgm    = issbase.q02_numcgm             ";
      $sql .= "               order by q123_sequencial                                                        ";
      $sql .= "              ) as x                                                                           ";
      $sql .= "       where (dl_ultima_movimentacao is null or dl_ultima_movimentacao in (2, 6))              ";
      
      
      if(!empty($iDepto)){
         
        $sql .= "             and {$iDepto} in (select q99_depto                                                ";
        $sql .= "                                 from isstipoalvaradepto                                       ";
        $sql .= "                                where q99_isstipoalvara = x.dl_tipo_alvara)                    ";                                                                                             
      }                                                                                               
     $sql2 = "";
     if($dbwhere==""){
       if($q123_sequencial!=null ){
         $sql2 .= "  and issalvara.q123_sequencial = $q123_sequencial "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = "  and $dbwhere";
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
   function sql_queryBaixa ( $q123_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issalvara ";
     $sql .= "      inner join issbase  on  issbase.q02_inscr = issalvara.q123_inscr";
     $sql .= "      inner join isstipoalvara  on  isstipoalvara.q98_sequencial = issalvara.q123_isstipoalvara";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = issbase.q02_numcgm";
     $sql .= "      inner join db_documentotemplate  on  db_documentotemplate.db82_sequencial = isstipoalvara.q98_documento";
     $sql .= "      inner join issgrupotipoalvara  on  issgrupotipoalvara.q97_sequencial = isstipoalvara.q98_issgrupotipoalvara";
     $sql .= "      inner join isstipoalvaradepto on q99_isstipoalvara = q123_isstipoalvara ";
     $sql2 = "";
     if($dbwhere==""){
       if($q123_sequencial!=null ){
         $sql2 .= " where issalvara.q123_sequencial = $q123_sequencial "; 
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
  /*
   * Função para pesquisa de
   * Enter description here ...
   * @param unknown_type $q123_sequencial
   * @param unknown_type $campos
   * @param unknown_type $ordem
   * @param unknown_type $dbwhere
   * @return string
   */
  function sql_queryConsultaRenovacao( $q123_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
    
    $sCampos = '';

    if($campos != "*" ){

      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){

        $sCampos .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }else{
      $sCampos .= $campos;
    }

    $sql  = "select distinct {$sCampos}, dl_Renovacoes from ( select  {$sCampos} ,         ";
    $sql .= "   q98_quantrenovacao,                                                        ";
    $sql .= "   (select count(*)                                                           ";
    $sql .= "      from issmovalvara                                                       ";
    $sql .= "     where issmovalvara.q120_issalvara = issalvara.q123_sequencial            "; 
    $sql .= "       and q120_isstipomovalvara = 4 ) -                                      ";
    $sql .= "    (select count(*)                                                           ";
    $sql .= "      from issmovalvara                                                       ";
    $sql .= "     where issmovalvara.q120_issalvara = issalvara.q123_sequencial            "; 
    $sql .= "       and q120_isstipomovalvara = 8 ) as dl_Renovacoes                       ";
    $sql .= " from issalvara                                                               ";
    $sql .= "   inner join issbase            on issbase.q02_inscr  = issalvara.q123_inscr ";
    $sql .= "   inner join cgm                on cgm.z01_numcgm     = issbase.q02_numcgm   ";
    $sql .= "   inner join isstipoalvara      on q98_sequencial     = q123_isstipoalvara   "; 
    $sql .= "   left  join issmovalvara       on q123_sequencial    = q120_issalvara       ";
    $sql .= "   left  join isstipoalvaradepto on q123_isstipoalvara = q99_isstipoalvara    ";
    $sql .= "                                                                              "; 
    $sql .= "   where q98_permiterenovacao is true                                         ";
    $sql .= "     and issalvara.q123_situacao = 1                                          ";
    
    $sql2 = "";
    if($dbwhere==""){
      if($q123_sequencial!=null ){
        $sql2 .= "  and issalvara.q123_sequencial = $q123_sequencial ";
      }
      }else if($dbwhere != ""){
      $sql2 = "  and $dbwhere";
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
      //echo $sql;
      $sql .= ") as x where dl_Renovacoes < q98_quantrenovacao";
      return $sql;
  }

  /**
   * Função de pesquisa para cancelamentos
   * @param string $sCampos - Campos do SQL
   * @param string $sWhere  - Filtro da Consulta SQL
   * @return string
   */
  function sql_queryConsultaCancelamento($sCampos = "", $sWhere = "") {

    if ($sCampos == "") {
      $sCampos = "*";
    }
    
    $sql  = " select {$sCampos}                                                                   ";
    $sql .= "   from (select *,                                                                   ";
    $sql .= "                (select q120_isstipomovalvara                                        ";
    $sql .= "                   from issmovalvara                                                 ";
    $sql .= "                  where q120_issalvara = q123_sequencial                             ";
    $sql .= "                    and q120_isstipomovalvara not in (3,5,6,7,8)                     ";
    $sql .= "                  order by q120_sequencial desc                                      ";
    $sql .= "                  limit 1                                                            ";
    $sql .= "                ) as cod_ult_mov                                                     ";
    $sql .= "           from issalvara                                                            ";
    $sql .= "                inner join issbase            on q02_inscr          = q123_inscr     ";
    $sql .= "                inner join cgm                on q02_numcgm         = z01_numcgm     ";
    $sql .= "                inner join isstipoalvara      on q123_isstipoalvara = q98_sequencial ";
    $sql .= "                inner join isstipoalvaradepto on q99_isstipoalvara  = q98_sequencial ";
    $sql .= "        ) as x                                                                       ";
    $sql .= "  where cod_ult_mov is not null                                                      ";
    if ($sWhere != "") {
      $sql .= " and {$sWhere} ";
    }
    return $sql;
  }
   
}
?>