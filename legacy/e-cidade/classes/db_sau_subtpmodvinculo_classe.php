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
//CLASSE DA ENTIDADE sau_subtpmodvinculo
class cl_sau_subtpmodvinculo { 
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
   var $sd54_i_vinculacao = 0; 
   var $sd54_i_tpvinculo = 0; 
   var $sd54_i_tpsubvinculo = 0; 
   var $sd54_v_descricao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd54_i_vinculacao = int4 = Código da Vinculação 
                 sd54_i_tpvinculo = int4 = Tipo do vinculo 
                 sd54_i_tpsubvinculo = int4 = Tipo Subvinculo 
                 sd54_v_descricao = varchar(60) = Descrição Subvinculo 
                 ";
   //funcao construtor da classe 
   function cl_sau_subtpmodvinculo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_subtpmodvinculo"); 
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
       $this->sd54_i_vinculacao = ($this->sd54_i_vinculacao == ""?@$GLOBALS["HTTP_POST_VARS"]["sd54_i_vinculacao"]:$this->sd54_i_vinculacao);
       $this->sd54_i_tpvinculo = ($this->sd54_i_tpvinculo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd54_i_tpvinculo"]:$this->sd54_i_tpvinculo);
       $this->sd54_i_tpsubvinculo = ($this->sd54_i_tpsubvinculo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd54_i_tpsubvinculo"]:$this->sd54_i_tpsubvinculo);
       $this->sd54_v_descricao = ($this->sd54_v_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["sd54_v_descricao"]:$this->sd54_v_descricao);
     }else{
       $this->sd54_i_vinculacao = ($this->sd54_i_vinculacao == ""?@$GLOBALS["HTTP_POST_VARS"]["sd54_i_vinculacao"]:$this->sd54_i_vinculacao);
       $this->sd54_i_tpvinculo = ($this->sd54_i_tpvinculo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd54_i_tpvinculo"]:$this->sd54_i_tpvinculo);
       $this->sd54_i_tpsubvinculo = ($this->sd54_i_tpsubvinculo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd54_i_tpsubvinculo"]:$this->sd54_i_tpsubvinculo);
     }
   }
   // funcao para inclusao
   function incluir ($sd54_i_vinculacao,$sd54_i_tpvinculo,$sd54_i_tpsubvinculo){ 
      $this->atualizacampos();
     if($this->sd54_v_descricao == null ){ 
       $this->erro_sql = " Campo Descrição Subvinculo nao Informado.";
       $this->erro_campo = "sd54_v_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->sd54_i_vinculacao = $sd54_i_vinculacao; 
       $this->sd54_i_tpvinculo = $sd54_i_tpvinculo; 
       $this->sd54_i_tpsubvinculo = $sd54_i_tpsubvinculo; 
     if(($this->sd54_i_vinculacao == null) || ($this->sd54_i_vinculacao == "") ){ 
       $this->erro_sql = " Campo sd54_i_vinculacao nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->sd54_i_tpvinculo == null) || ($this->sd54_i_tpvinculo == "") ){ 
       $this->erro_sql = " Campo sd54_i_tpvinculo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->sd54_i_tpsubvinculo == null) || ($this->sd54_i_tpsubvinculo == "") ){ 
       $this->erro_sql = " Campo sd54_i_tpsubvinculo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sau_subtpmodvinculo(
                                       sd54_i_vinculacao 
                                      ,sd54_i_tpvinculo 
                                      ,sd54_i_tpsubvinculo 
                                      ,sd54_v_descricao 
                       )
                values (
                                $this->sd54_i_vinculacao 
                               ,$this->sd54_i_tpvinculo 
                               ,$this->sd54_i_tpsubvinculo 
                               ,'$this->sd54_v_descricao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "subtpmodvinculo ($this->sd54_i_vinculacao."-".$this->sd54_i_tpvinculo."-".$this->sd54_i_tpsubvinculo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "subtpmodvinculo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "subtpmodvinculo ($this->sd54_i_vinculacao."-".$this->sd54_i_tpvinculo."-".$this->sd54_i_tpsubvinculo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd54_i_vinculacao."-".$this->sd54_i_tpvinculo."-".$this->sd54_i_tpsubvinculo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sd54_i_vinculacao,$this->sd54_i_tpvinculo,$this->sd54_i_tpsubvinculo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11465,'$this->sd54_i_vinculacao','I')");
       $resac = db_query("insert into db_acountkey values($acount,11466,'$this->sd54_i_tpvinculo','I')");
       $resac = db_query("insert into db_acountkey values($acount,11467,'$this->sd54_i_tpsubvinculo','I')");
       $resac = db_query("insert into db_acount values($acount,1972,11465,'','".AddSlashes(pg_result($resaco,0,'sd54_i_vinculacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1972,11466,'','".AddSlashes(pg_result($resaco,0,'sd54_i_tpvinculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1972,11467,'','".AddSlashes(pg_result($resaco,0,'sd54_i_tpsubvinculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1972,11468,'','".AddSlashes(pg_result($resaco,0,'sd54_v_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($sd54_i_vinculacao=null,$sd54_i_tpvinculo=null,$sd54_i_tpsubvinculo=null) { 
      $this->atualizacampos();
     $sql = " update sau_subtpmodvinculo set ";
     $virgula = "";
     if(trim($this->sd54_i_vinculacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd54_i_vinculacao"])){ 
       $sql  .= $virgula." sd54_i_vinculacao = $this->sd54_i_vinculacao ";
       $virgula = ",";
       if(trim($this->sd54_i_vinculacao) == null ){ 
         $this->erro_sql = " Campo Código da Vinculação nao Informado.";
         $this->erro_campo = "sd54_i_vinculacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd54_i_tpvinculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd54_i_tpvinculo"])){ 
       $sql  .= $virgula." sd54_i_tpvinculo = $this->sd54_i_tpvinculo ";
       $virgula = ",";
       if(trim($this->sd54_i_tpvinculo) == null ){ 
         $this->erro_sql = " Campo Tipo do vinculo nao Informado.";
         $this->erro_campo = "sd54_i_tpvinculo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd54_i_tpsubvinculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd54_i_tpsubvinculo"])){ 
       $sql  .= $virgula." sd54_i_tpsubvinculo = $this->sd54_i_tpsubvinculo ";
       $virgula = ",";
       if(trim($this->sd54_i_tpsubvinculo) == null ){ 
         $this->erro_sql = " Campo Tipo Subvinculo nao Informado.";
         $this->erro_campo = "sd54_i_tpsubvinculo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd54_v_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd54_v_descricao"])){ 
       $sql  .= $virgula." sd54_v_descricao = '$this->sd54_v_descricao' ";
       $virgula = ",";
       if(trim($this->sd54_v_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição Subvinculo nao Informado.";
         $this->erro_campo = "sd54_v_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($sd54_i_vinculacao!=null){
       $sql .= " sd54_i_vinculacao = $this->sd54_i_vinculacao";
     }
     if($sd54_i_tpvinculo!=null){
       $sql .= " and  sd54_i_tpvinculo = $this->sd54_i_tpvinculo";
     }
     if($sd54_i_tpsubvinculo!=null){
       $sql .= " and  sd54_i_tpsubvinculo = $this->sd54_i_tpsubvinculo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sd54_i_vinculacao,$this->sd54_i_tpvinculo,$this->sd54_i_tpsubvinculo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11465,'$this->sd54_i_vinculacao','A')");
         $resac = db_query("insert into db_acountkey values($acount,11466,'$this->sd54_i_tpvinculo','A')");
         $resac = db_query("insert into db_acountkey values($acount,11467,'$this->sd54_i_tpsubvinculo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd54_i_vinculacao"]))
           $resac = db_query("insert into db_acount values($acount,1972,11465,'".AddSlashes(pg_result($resaco,$conresaco,'sd54_i_vinculacao'))."','$this->sd54_i_vinculacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd54_i_tpvinculo"]))
           $resac = db_query("insert into db_acount values($acount,1972,11466,'".AddSlashes(pg_result($resaco,$conresaco,'sd54_i_tpvinculo'))."','$this->sd54_i_tpvinculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd54_i_tpsubvinculo"]))
           $resac = db_query("insert into db_acount values($acount,1972,11467,'".AddSlashes(pg_result($resaco,$conresaco,'sd54_i_tpsubvinculo'))."','$this->sd54_i_tpsubvinculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd54_v_descricao"]))
           $resac = db_query("insert into db_acount values($acount,1972,11468,'".AddSlashes(pg_result($resaco,$conresaco,'sd54_v_descricao'))."','$this->sd54_v_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "subtpmodvinculo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd54_i_vinculacao."-".$this->sd54_i_tpvinculo."-".$this->sd54_i_tpsubvinculo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "subtpmodvinculo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd54_i_vinculacao."-".$this->sd54_i_tpvinculo."-".$this->sd54_i_tpsubvinculo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd54_i_vinculacao."-".$this->sd54_i_tpvinculo."-".$this->sd54_i_tpsubvinculo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($sd54_i_vinculacao=null,$sd54_i_tpvinculo=null,$sd54_i_tpsubvinculo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sd54_i_vinculacao,$sd54_i_tpvinculo,$sd54_i_tpsubvinculo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11465,'$sd54_i_vinculacao','E')");
         $resac = db_query("insert into db_acountkey values($acount,11466,'$sd54_i_tpvinculo','E')");
         $resac = db_query("insert into db_acountkey values($acount,11467,'$sd54_i_tpsubvinculo','E')");
         $resac = db_query("insert into db_acount values($acount,1972,11465,'','".AddSlashes(pg_result($resaco,$iresaco,'sd54_i_vinculacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1972,11466,'','".AddSlashes(pg_result($resaco,$iresaco,'sd54_i_tpvinculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1972,11467,'','".AddSlashes(pg_result($resaco,$iresaco,'sd54_i_tpsubvinculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1972,11468,'','".AddSlashes(pg_result($resaco,$iresaco,'sd54_v_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sau_subtpmodvinculo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sd54_i_vinculacao != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd54_i_vinculacao = $sd54_i_vinculacao ";
        }
        if($sd54_i_tpvinculo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd54_i_tpvinculo = $sd54_i_tpvinculo ";
        }
        if($sd54_i_tpsubvinculo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd54_i_tpsubvinculo = $sd54_i_tpsubvinculo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "subtpmodvinculo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd54_i_vinculacao."-".$sd54_i_tpvinculo."-".$sd54_i_tpsubvinculo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "subtpmodvinculo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd54_i_vinculacao."-".$sd54_i_tpvinculo."-".$sd54_i_tpsubvinculo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd54_i_vinculacao."-".$sd54_i_tpvinculo."-".$sd54_i_tpsubvinculo;
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
        $this->erro_sql   = "Record Vazio na Tabela:sau_subtpmodvinculo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $sd54_i_vinculacao=null,$sd54_i_tpvinculo=null,$sd54_i_tpsubvinculo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_subtpmodvinculo ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd54_i_vinculacao!=null ){
         $sql2 .= " where sau_subtpmodvinculo.sd54_i_vinculacao = $sd54_i_vinculacao "; 
       } 
       if($sd54_i_tpvinculo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " sau_subtpmodvinculo.sd54_i_tpvinculo = $sd54_i_tpvinculo "; 
       } 
       if($sd54_i_tpsubvinculo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " sau_subtpmodvinculo.sd54_i_tpsubvinculo = $sd54_i_tpsubvinculo "; 
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
   function sql_query_file ( $sd54_i_vinculacao=null,$sd54_i_tpvinculo=null,$sd54_i_tpsubvinculo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_subtpmodvinculo ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd54_i_vinculacao!=null ){
         $sql2 .= " where sau_subtpmodvinculo.sd54_i_vinculacao = $sd54_i_vinculacao "; 
       } 
       if($sd54_i_tpvinculo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " sau_subtpmodvinculo.sd54_i_tpvinculo = $sd54_i_tpvinculo "; 
       } 
       if($sd54_i_tpsubvinculo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " sau_subtpmodvinculo.sd54_i_tpsubvinculo = $sd54_i_tpsubvinculo "; 
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