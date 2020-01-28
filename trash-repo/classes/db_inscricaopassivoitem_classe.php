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

//MODULO: contabilidade
//CLASSE DA ENTIDADE inscricaopassivoitem
class cl_inscricaopassivoitem { 
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
   var $c38_sequencial = 0; 
   var $c38_inscricaopassivo = 0; 
   var $c38_pcmater = 0; 
   var $c38_quantidade = 0; 
   var $c38_valorunitario = 0; 
   var $c38_valortotal = 0; 
   var $c38_observacao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c38_sequencial = int4 = Item Iscrição Passiva 
                 c38_inscricaopassivo = int4 = Inscrição Passiva 
                 c38_pcmater = int4 = Item 
                 c38_quantidade = int4 = Quantidade 
                 c38_valorunitario = numeric(10) = Valor unitário 
                 c38_valortotal = numeric(10) = Valor total 
                 c38_observacao = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_inscricaopassivoitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("inscricaopassivoitem"); 
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
       $this->c38_sequencial = ($this->c38_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c38_sequencial"]:$this->c38_sequencial);
       $this->c38_inscricaopassivo = ($this->c38_inscricaopassivo == ""?@$GLOBALS["HTTP_POST_VARS"]["c38_inscricaopassivo"]:$this->c38_inscricaopassivo);
       $this->c38_pcmater = ($this->c38_pcmater == ""?@$GLOBALS["HTTP_POST_VARS"]["c38_pcmater"]:$this->c38_pcmater);
       $this->c38_quantidade = ($this->c38_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["c38_quantidade"]:$this->c38_quantidade);
       $this->c38_valorunitario = ($this->c38_valorunitario == ""?@$GLOBALS["HTTP_POST_VARS"]["c38_valorunitario"]:$this->c38_valorunitario);
       $this->c38_valortotal = ($this->c38_valortotal == ""?@$GLOBALS["HTTP_POST_VARS"]["c38_valortotal"]:$this->c38_valortotal);
       $this->c38_observacao = ($this->c38_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["c38_observacao"]:$this->c38_observacao);
     }else{
       $this->c38_sequencial = ($this->c38_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c38_sequencial"]:$this->c38_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($c38_sequencial){ 
      $this->atualizacampos();
     if($this->c38_inscricaopassivo == null ){ 
       $this->erro_sql = " Campo Inscrição Passiva nao Informado.";
       $this->erro_campo = "c38_inscricaopassivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c38_pcmater == null ){ 
       $this->erro_sql = " Campo Item nao Informado.";
       $this->erro_campo = "c38_pcmater";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c38_quantidade == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "c38_quantidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c38_valorunitario == null ){ 
       $this->erro_sql = " Campo Valor unitário nao Informado.";
       $this->erro_campo = "c38_valorunitario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c38_valortotal == null ){ 
       $this->erro_sql = " Campo Valor total nao Informado.";
       $this->erro_campo = "c38_valortotal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c38_sequencial == "" || $c38_sequencial == null ){
       $result = db_query("select nextval('inscricaopassivoitem_c38_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: inscricaopassivoitem_c38_sequencial_seq do campo: c38_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->c38_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from inscricaopassivoitem_c38_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c38_sequencial)){
         $this->erro_sql = " Campo c38_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c38_sequencial = $c38_sequencial; 
       }
     }
     if(($this->c38_sequencial == null) || ($this->c38_sequencial == "") ){ 
       $this->erro_sql = " Campo c38_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into inscricaopassivoitem(
                                       c38_sequencial 
                                      ,c38_inscricaopassivo 
                                      ,c38_pcmater 
                                      ,c38_quantidade 
                                      ,c38_valorunitario 
                                      ,c38_valortotal 
                                      ,c38_observacao 
                       )
                values (
                                $this->c38_sequencial 
                               ,$this->c38_inscricaopassivo 
                               ,$this->c38_pcmater 
                               ,$this->c38_quantidade 
                               ,$this->c38_valorunitario 
                               ,$this->c38_valortotal 
                               ,'$this->c38_observacao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Itens da Inscrição Passiva ($this->c38_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Itens da Inscrição Passiva já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Itens da Inscrição Passiva ($this->c38_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c38_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c38_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19000,'$this->c38_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3380,19000,'','".AddSlashes(pg_result($resaco,0,'c38_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3380,19001,'','".AddSlashes(pg_result($resaco,0,'c38_inscricaopassivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3380,19003,'','".AddSlashes(pg_result($resaco,0,'c38_pcmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3380,19004,'','".AddSlashes(pg_result($resaco,0,'c38_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3380,19005,'','".AddSlashes(pg_result($resaco,0,'c38_valorunitario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3380,19006,'','".AddSlashes(pg_result($resaco,0,'c38_valortotal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3380,19007,'','".AddSlashes(pg_result($resaco,0,'c38_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c38_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update inscricaopassivoitem set ";
     $virgula = "";
     if(trim($this->c38_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c38_sequencial"])){ 
       $sql  .= $virgula." c38_sequencial = $this->c38_sequencial ";
       $virgula = ",";
       if(trim($this->c38_sequencial) == null ){ 
         $this->erro_sql = " Campo Item Iscrição Passiva nao Informado.";
         $this->erro_campo = "c38_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c38_inscricaopassivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c38_inscricaopassivo"])){ 
       $sql  .= $virgula." c38_inscricaopassivo = $this->c38_inscricaopassivo ";
       $virgula = ",";
       if(trim($this->c38_inscricaopassivo) == null ){ 
         $this->erro_sql = " Campo Inscrição Passiva nao Informado.";
         $this->erro_campo = "c38_inscricaopassivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c38_pcmater)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c38_pcmater"])){ 
       $sql  .= $virgula." c38_pcmater = $this->c38_pcmater ";
       $virgula = ",";
       if(trim($this->c38_pcmater) == null ){ 
         $this->erro_sql = " Campo Item nao Informado.";
         $this->erro_campo = "c38_pcmater";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c38_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c38_quantidade"])){ 
       $sql  .= $virgula." c38_quantidade = $this->c38_quantidade ";
       $virgula = ",";
       if(trim($this->c38_quantidade) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "c38_quantidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c38_valorunitario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c38_valorunitario"])){ 
       $sql  .= $virgula." c38_valorunitario = $this->c38_valorunitario ";
       $virgula = ",";
       if(trim($this->c38_valorunitario) == null ){ 
         $this->erro_sql = " Campo Valor unitário nao Informado.";
         $this->erro_campo = "c38_valorunitario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c38_valortotal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c38_valortotal"])){ 
       $sql  .= $virgula." c38_valortotal = $this->c38_valortotal ";
       $virgula = ",";
       if(trim($this->c38_valortotal) == null ){ 
         $this->erro_sql = " Campo Valor total nao Informado.";
         $this->erro_campo = "c38_valortotal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c38_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c38_observacao"])){ 
       $sql  .= $virgula." c38_observacao = '$this->c38_observacao' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($c38_sequencial!=null){
       $sql .= " c38_sequencial = $this->c38_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c38_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19000,'$this->c38_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c38_sequencial"]) || $this->c38_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3380,19000,'".AddSlashes(pg_result($resaco,$conresaco,'c38_sequencial'))."','$this->c38_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c38_inscricaopassivo"]) || $this->c38_inscricaopassivo != "")
           $resac = db_query("insert into db_acount values($acount,3380,19001,'".AddSlashes(pg_result($resaco,$conresaco,'c38_inscricaopassivo'))."','$this->c38_inscricaopassivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c38_pcmater"]) || $this->c38_pcmater != "")
           $resac = db_query("insert into db_acount values($acount,3380,19003,'".AddSlashes(pg_result($resaco,$conresaco,'c38_pcmater'))."','$this->c38_pcmater',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c38_quantidade"]) || $this->c38_quantidade != "")
           $resac = db_query("insert into db_acount values($acount,3380,19004,'".AddSlashes(pg_result($resaco,$conresaco,'c38_quantidade'))."','$this->c38_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c38_valorunitario"]) || $this->c38_valorunitario != "")
           $resac = db_query("insert into db_acount values($acount,3380,19005,'".AddSlashes(pg_result($resaco,$conresaco,'c38_valorunitario'))."','$this->c38_valorunitario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c38_valortotal"]) || $this->c38_valortotal != "")
           $resac = db_query("insert into db_acount values($acount,3380,19006,'".AddSlashes(pg_result($resaco,$conresaco,'c38_valortotal'))."','$this->c38_valortotal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c38_observacao"]) || $this->c38_observacao != "")
           $resac = db_query("insert into db_acount values($acount,3380,19007,'".AddSlashes(pg_result($resaco,$conresaco,'c38_observacao'))."','$this->c38_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens da Inscrição Passiva nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c38_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens da Inscrição Passiva nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c38_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c38_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c38_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c38_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19000,'$c38_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3380,19000,'','".AddSlashes(pg_result($resaco,$iresaco,'c38_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3380,19001,'','".AddSlashes(pg_result($resaco,$iresaco,'c38_inscricaopassivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3380,19003,'','".AddSlashes(pg_result($resaco,$iresaco,'c38_pcmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3380,19004,'','".AddSlashes(pg_result($resaco,$iresaco,'c38_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3380,19005,'','".AddSlashes(pg_result($resaco,$iresaco,'c38_valorunitario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3380,19006,'','".AddSlashes(pg_result($resaco,$iresaco,'c38_valortotal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3380,19007,'','".AddSlashes(pg_result($resaco,$iresaco,'c38_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from inscricaopassivoitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c38_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c38_sequencial = $c38_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens da Inscrição Passiva nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c38_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens da Inscrição Passiva nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c38_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c38_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:inscricaopassivoitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $c38_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from inscricaopassivoitem ";
     $sql .= "      inner join pcmater  on  pcmater.pc01_codmater = inscricaopassivoitem.c38_pcmater";
     $sql .= "      inner join inscricaopassivo  on  inscricaopassivo.c36_sequencial = inscricaopassivoitem.c38_inscricaopassivo";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = pcmater.pc01_id_usuario";
     $sql .= "      inner join pcsubgrupo  on  pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = inscricaopassivo.c36_cgm";
     $sql .= "      inner join db_config  on  db_config.codigo = inscricaopassivo.c36_instit";
     $sql .= "      inner join db_usuarios  as a on   a.id_usuario = inscricaopassivo.c36_db_usuarios";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = inscricaopassivo.c36_codele and  orcelemento.o56_anousu = inscricaopassivo.c36_anousu";
     $sql .= "      inner join conhist  on  conhist.c50_codhist = inscricaopassivo.c36_conhist";
     $sql2 = "";
     if($dbwhere==""){
       if($c38_sequencial!=null ){
         $sql2 .= " where inscricaopassivoitem.c38_sequencial = $c38_sequencial "; 
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
   function sql_query_file ( $c38_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from inscricaopassivoitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($c38_sequencial!=null ){
         $sql2 .= " where inscricaopassivoitem.c38_sequencial = $c38_sequencial "; 
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