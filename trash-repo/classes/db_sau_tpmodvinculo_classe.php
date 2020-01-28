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

//MODULO: saude
//CLASSE DA ENTIDADE sau_tpmodvinculo
class cl_sau_tpmodvinculo { 
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
   var $sd53_i_vinculacao = 0; 
   var $sd53_i_tpvinculo = 0; 
   var $sd53_v_descrvinculo = null; 
   var $sd53_i_tpesfadm = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd53_i_vinculacao = int4 = Vinculação 
                 sd53_i_tpvinculo = int4 = Tipo Vínculo 
                 sd53_v_descrvinculo = varchar(60) = Descrição do Vínculo 
                 sd53_i_tpesfadm = int4 = Tipo Esfera Administrativa 
                 ";
   //funcao construtor da classe 
   function cl_sau_tpmodvinculo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_tpmodvinculo"); 
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
       $this->sd53_i_vinculacao = ($this->sd53_i_vinculacao == ""?@$GLOBALS["HTTP_POST_VARS"]["sd53_i_vinculacao"]:$this->sd53_i_vinculacao);
       $this->sd53_i_tpvinculo = ($this->sd53_i_tpvinculo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd53_i_tpvinculo"]:$this->sd53_i_tpvinculo);
       $this->sd53_v_descrvinculo = ($this->sd53_v_descrvinculo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd53_v_descrvinculo"]:$this->sd53_v_descrvinculo);
       $this->sd53_i_tpesfadm = ($this->sd53_i_tpesfadm == ""?@$GLOBALS["HTTP_POST_VARS"]["sd53_i_tpesfadm"]:$this->sd53_i_tpesfadm);
     }else{
       $this->sd53_i_vinculacao = ($this->sd53_i_vinculacao == ""?@$GLOBALS["HTTP_POST_VARS"]["sd53_i_vinculacao"]:$this->sd53_i_vinculacao);
       $this->sd53_i_tpvinculo = ($this->sd53_i_tpvinculo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd53_i_tpvinculo"]:$this->sd53_i_tpvinculo);
     }
   }
   // funcao para inclusao
   function incluir ($sd53_i_vinculacao,$sd53_i_tpvinculo){ 
      $this->atualizacampos();
     if($this->sd53_v_descrvinculo == null ){ 
       $this->erro_sql = " Campo Descrição do Vínculo nao Informado.";
       $this->erro_campo = "sd53_v_descrvinculo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd53_i_tpesfadm == null ){ 
       $this->erro_sql = " Campo Tipo Esfera Administrativa nao Informado.";
       $this->erro_campo = "sd53_i_tpesfadm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->sd53_i_vinculacao = $sd53_i_vinculacao; 
       $this->sd53_i_tpvinculo = $sd53_i_tpvinculo; 
     if(($this->sd53_i_vinculacao == null) || ($this->sd53_i_vinculacao == "") ){ 
       $this->erro_sql = " Campo sd53_i_vinculacao nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->sd53_i_tpvinculo == null) || ($this->sd53_i_tpvinculo == "") ){ 
       $this->erro_sql = " Campo sd53_i_tpvinculo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sau_tpmodvinculo(
                                       sd53_i_vinculacao 
                                      ,sd53_i_tpvinculo 
                                      ,sd53_v_descrvinculo 
                                      ,sd53_i_tpesfadm 
                       )
                values (
                                $this->sd53_i_vinculacao 
                               ,$this->sd53_i_tpvinculo 
                               ,'$this->sd53_v_descrvinculo' 
                               ,$this->sd53_i_tpesfadm 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "tpmodvinculo ($this->sd53_i_vinculacao."-".$this->sd53_i_tpvinculo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "tpmodvinculo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "tpmodvinculo ($this->sd53_i_vinculacao."-".$this->sd53_i_tpvinculo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd53_i_vinculacao."-".$this->sd53_i_tpvinculo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sd53_i_vinculacao,$this->sd53_i_tpvinculo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11469,'$this->sd53_i_vinculacao','I')");
       $resac = db_query("insert into db_acountkey values($acount,11462,'$this->sd53_i_tpvinculo','I')");
       $resac = db_query("insert into db_acount values($acount,1971,11469,'','".AddSlashes(pg_result($resaco,0,'sd53_i_vinculacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1971,11462,'','".AddSlashes(pg_result($resaco,0,'sd53_i_tpvinculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1971,11463,'','".AddSlashes(pg_result($resaco,0,'sd53_v_descrvinculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1971,11464,'','".AddSlashes(pg_result($resaco,0,'sd53_i_tpesfadm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($sd53_i_vinculacao=null,$sd53_i_tpvinculo=null) { 
      $this->atualizacampos();
     $sql = " update sau_tpmodvinculo set ";
     $virgula = "";
     if(trim($this->sd53_i_vinculacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd53_i_vinculacao"])){ 
       $sql  .= $virgula." sd53_i_vinculacao = $this->sd53_i_vinculacao ";
       $virgula = ",";
       if(trim($this->sd53_i_vinculacao) == null ){ 
         $this->erro_sql = " Campo Vinculação nao Informado.";
         $this->erro_campo = "sd53_i_vinculacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd53_i_tpvinculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd53_i_tpvinculo"])){ 
       $sql  .= $virgula." sd53_i_tpvinculo = $this->sd53_i_tpvinculo ";
       $virgula = ",";
       if(trim($this->sd53_i_tpvinculo) == null ){ 
         $this->erro_sql = " Campo Tipo Vínculo nao Informado.";
         $this->erro_campo = "sd53_i_tpvinculo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd53_v_descrvinculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd53_v_descrvinculo"])){ 
       $sql  .= $virgula." sd53_v_descrvinculo = '$this->sd53_v_descrvinculo' ";
       $virgula = ",";
       if(trim($this->sd53_v_descrvinculo) == null ){ 
         $this->erro_sql = " Campo Descrição do Vínculo nao Informado.";
         $this->erro_campo = "sd53_v_descrvinculo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd53_i_tpesfadm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd53_i_tpesfadm"])){ 
       $sql  .= $virgula." sd53_i_tpesfadm = $this->sd53_i_tpesfadm ";
       $virgula = ",";
       if(trim($this->sd53_i_tpesfadm) == null ){ 
         $this->erro_sql = " Campo Tipo Esfera Administrativa nao Informado.";
         $this->erro_campo = "sd53_i_tpesfadm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($sd53_i_vinculacao!=null){
       $sql .= " sd53_i_vinculacao = $this->sd53_i_vinculacao";
     }
     if($sd53_i_tpvinculo!=null){
       $sql .= " and  sd53_i_tpvinculo = $this->sd53_i_tpvinculo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sd53_i_vinculacao,$this->sd53_i_tpvinculo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11469,'$this->sd53_i_vinculacao','A')");
         $resac = db_query("insert into db_acountkey values($acount,11462,'$this->sd53_i_tpvinculo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd53_i_vinculacao"]))
           $resac = db_query("insert into db_acount values($acount,1971,11469,'".AddSlashes(pg_result($resaco,$conresaco,'sd53_i_vinculacao'))."','$this->sd53_i_vinculacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd53_i_tpvinculo"]))
           $resac = db_query("insert into db_acount values($acount,1971,11462,'".AddSlashes(pg_result($resaco,$conresaco,'sd53_i_tpvinculo'))."','$this->sd53_i_tpvinculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd53_v_descrvinculo"]))
           $resac = db_query("insert into db_acount values($acount,1971,11463,'".AddSlashes(pg_result($resaco,$conresaco,'sd53_v_descrvinculo'))."','$this->sd53_v_descrvinculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd53_i_tpesfadm"]))
           $resac = db_query("insert into db_acount values($acount,1971,11464,'".AddSlashes(pg_result($resaco,$conresaco,'sd53_i_tpesfadm'))."','$this->sd53_i_tpesfadm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tpmodvinculo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd53_i_vinculacao."-".$this->sd53_i_tpvinculo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tpmodvinculo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd53_i_vinculacao."-".$this->sd53_i_tpvinculo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd53_i_vinculacao."-".$this->sd53_i_tpvinculo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($sd53_i_vinculacao=null,$sd53_i_tpvinculo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sd53_i_vinculacao,$sd53_i_tpvinculo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11469,'$sd53_i_vinculacao','E')");
         $resac = db_query("insert into db_acountkey values($acount,11462,'$sd53_i_tpvinculo','E')");
         $resac = db_query("insert into db_acount values($acount,1971,11469,'','".AddSlashes(pg_result($resaco,$iresaco,'sd53_i_vinculacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1971,11462,'','".AddSlashes(pg_result($resaco,$iresaco,'sd53_i_tpvinculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1971,11463,'','".AddSlashes(pg_result($resaco,$iresaco,'sd53_v_descrvinculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1971,11464,'','".AddSlashes(pg_result($resaco,$iresaco,'sd53_i_tpesfadm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sau_tpmodvinculo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sd53_i_vinculacao != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd53_i_vinculacao = $sd53_i_vinculacao ";
        }
        if($sd53_i_tpvinculo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd53_i_tpvinculo = $sd53_i_tpvinculo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tpmodvinculo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd53_i_vinculacao."-".$sd53_i_tpvinculo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tpmodvinculo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd53_i_vinculacao."-".$sd53_i_tpvinculo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd53_i_vinculacao."-".$sd53_i_tpvinculo;
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
        $this->erro_sql   = "Record Vazio na Tabela:sau_tpmodvinculo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $sd53_i_vinculacao=null,$sd53_i_tpvinculo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_tpmodvinculo ";
     $sql .= "      left join sau_esferaadmin  on  sau_esferaadmin.sd37_i_cod_esfadm = sau_tpmodvinculo.sd53_i_tpesfadm";
     $sql2 = "";
     if($dbwhere==""){
       if($sd53_i_vinculacao!=null ){
         $sql2 .= " where sau_tpmodvinculo.sd53_i_vinculacao = $sd53_i_vinculacao "; 
       } 
       if($sd53_i_tpvinculo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " sau_tpmodvinculo.sd53_i_tpvinculo = $sd53_i_tpvinculo "; 
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
   function sql_query_file ( $sd53_i_vinculacao=null,$sd53_i_tpvinculo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_tpmodvinculo ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd53_i_vinculacao!=null ){
         $sql2 .= " where sau_tpmodvinculo.sd53_i_vinculacao = $sd53_i_vinculacao "; 
       } 
       if($sd53_i_tpvinculo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " sau_tpmodvinculo.sd53_i_tpvinculo = $sd53_i_tpvinculo "; 
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