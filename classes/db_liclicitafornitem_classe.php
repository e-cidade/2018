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

//MODULO: licitação
//CLASSE DA ENTIDADE liclicitafornitem
class cl_liclicitafornitem { 
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
   var $l23_codigo = 0; 
   var $l23_codliclicitaforne = 0; 
   var $l23_codliclicitem = 0; 
   var $l23_obs = null; 
   var $l23_condpag = null; 
   var $l23_prazo = null; 
   var $l23_garantia = null; 
   var $l23_quantcot = 0; 
   var $l23_valcot = 0; 
   var $l23_pontuacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 l23_codigo = float8 = Cod. Sequencial 
                 l23_codliclicitaforne = int8 = Cod. Sequencial 
                 l23_codliclicitem = int8 = Cod. Sequencial 
                 l23_obs = text = Observação 
                 l23_condpag = text = Condição de Pagamento 
                 l23_prazo = text = Prazo 
                 l23_garantia = text = Garantia 
                 l23_quantcot = int4 = Quantidade Cotada 
                 l23_valcot = float8 = Valor Cotado 
                 l23_pontuacao = int4 = Pontuação 
                 ";
   //funcao construtor da classe 
   function cl_liclicitafornitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("liclicitafornitem"); 
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
       $this->l23_codigo = ($this->l23_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["l23_codigo"]:$this->l23_codigo);
       $this->l23_codliclicitaforne = ($this->l23_codliclicitaforne == ""?@$GLOBALS["HTTP_POST_VARS"]["l23_codliclicitaforne"]:$this->l23_codliclicitaforne);
       $this->l23_codliclicitem = ($this->l23_codliclicitem == ""?@$GLOBALS["HTTP_POST_VARS"]["l23_codliclicitem"]:$this->l23_codliclicitem);
       $this->l23_obs = ($this->l23_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["l23_obs"]:$this->l23_obs);
       $this->l23_condpag = ($this->l23_condpag == ""?@$GLOBALS["HTTP_POST_VARS"]["l23_condpag"]:$this->l23_condpag);
       $this->l23_prazo = ($this->l23_prazo == ""?@$GLOBALS["HTTP_POST_VARS"]["l23_prazo"]:$this->l23_prazo);
       $this->l23_garantia = ($this->l23_garantia == ""?@$GLOBALS["HTTP_POST_VARS"]["l23_garantia"]:$this->l23_garantia);
       $this->l23_quantcot = ($this->l23_quantcot == ""?@$GLOBALS["HTTP_POST_VARS"]["l23_quantcot"]:$this->l23_quantcot);
       $this->l23_valcot = ($this->l23_valcot == ""?@$GLOBALS["HTTP_POST_VARS"]["l23_valcot"]:$this->l23_valcot);
       $this->l23_pontuacao = ($this->l23_pontuacao == ""?@$GLOBALS["HTTP_POST_VARS"]["l23_pontuacao"]:$this->l23_pontuacao);
     }else{
       $this->l23_codigo = ($this->l23_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["l23_codigo"]:$this->l23_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($l23_codigo){ 
      $this->atualizacampos();
     if($this->l23_codliclicitaforne == null ){ 
       $this->erro_sql = " Campo Cod. Sequencial nao Informado.";
       $this->erro_campo = "l23_codliclicitaforne";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l23_codliclicitem == null ){ 
       $this->erro_sql = " Campo Cod. Sequencial nao Informado.";
       $this->erro_campo = "l23_codliclicitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l23_condpag == null ){ 
       $this->erro_sql = " Campo Condição de Pagamento nao Informado.";
       $this->erro_campo = "l23_condpag";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l23_prazo == null ){ 
       $this->erro_sql = " Campo Prazo nao Informado.";
       $this->erro_campo = "l23_prazo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l23_garantia == null ){ 
       $this->erro_sql = " Campo Garantia nao Informado.";
       $this->erro_campo = "l23_garantia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l23_quantcot == null ){ 
       $this->erro_sql = " Campo Quantidade Cotada nao Informado.";
       $this->erro_campo = "l23_quantcot";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l23_valcot == null ){ 
       $this->erro_sql = " Campo Valor Cotado nao Informado.";
       $this->erro_campo = "l23_valcot";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l23_pontuacao == null ){ 
       $this->erro_sql = " Campo Pontuação nao Informado.";
       $this->erro_campo = "l23_pontuacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($l23_codigo == "" || $l23_codigo == null ){
       $result = db_query("select nextval('liclicitafornitem_l23_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: liclicitafornitem_l23_codigo_seq do campo: l23_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->l23_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from liclicitafornitem_l23_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $l23_codigo)){
         $this->erro_sql = " Campo l23_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->l23_codigo = $l23_codigo; 
       }
     }
     if(($this->l23_codigo == null) || ($this->l23_codigo == "") ){ 
       $this->erro_sql = " Campo l23_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into liclicitafornitem(
                                       l23_codigo 
                                      ,l23_codliclicitaforne 
                                      ,l23_codliclicitem 
                                      ,l23_obs 
                                      ,l23_condpag 
                                      ,l23_prazo 
                                      ,l23_garantia 
                                      ,l23_quantcot 
                                      ,l23_valcot 
                                      ,l23_pontuacao 
                       )
                values (
                                $this->l23_codigo 
                               ,$this->l23_codliclicitaforne 
                               ,$this->l23_codliclicitem 
                               ,'$this->l23_obs' 
                               ,'$this->l23_condpag' 
                               ,'$this->l23_prazo' 
                               ,'$this->l23_garantia' 
                               ,$this->l23_quantcot 
                               ,$this->l23_valcot 
                               ,$this->l23_pontuacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "liclicitafornitem ($this->l23_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "liclicitafornitem já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "liclicitafornitem ($this->l23_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l23_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->l23_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7606,'$this->l23_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1263,7606,'','".AddSlashes(pg_result($resaco,0,'l23_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1263,7607,'','".AddSlashes(pg_result($resaco,0,'l23_codliclicitaforne'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1263,7608,'','".AddSlashes(pg_result($resaco,0,'l23_codliclicitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1263,7609,'','".AddSlashes(pg_result($resaco,0,'l23_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1263,7610,'','".AddSlashes(pg_result($resaco,0,'l23_condpag'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1263,7611,'','".AddSlashes(pg_result($resaco,0,'l23_prazo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1263,7612,'','".AddSlashes(pg_result($resaco,0,'l23_garantia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1263,7613,'','".AddSlashes(pg_result($resaco,0,'l23_quantcot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1263,7614,'','".AddSlashes(pg_result($resaco,0,'l23_valcot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1263,7615,'','".AddSlashes(pg_result($resaco,0,'l23_pontuacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($l23_codigo=null) { 
      $this->atualizacampos();
     $sql = " update liclicitafornitem set ";
     $virgula = "";
     if(trim($this->l23_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l23_codigo"])){ 
       $sql  .= $virgula." l23_codigo = $this->l23_codigo ";
       $virgula = ",";
       if(trim($this->l23_codigo) == null ){ 
         $this->erro_sql = " Campo Cod. Sequencial nao Informado.";
         $this->erro_campo = "l23_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l23_codliclicitaforne)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l23_codliclicitaforne"])){ 
       $sql  .= $virgula." l23_codliclicitaforne = $this->l23_codliclicitaforne ";
       $virgula = ",";
       if(trim($this->l23_codliclicitaforne) == null ){ 
         $this->erro_sql = " Campo Cod. Sequencial nao Informado.";
         $this->erro_campo = "l23_codliclicitaforne";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l23_codliclicitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l23_codliclicitem"])){ 
       $sql  .= $virgula." l23_codliclicitem = $this->l23_codliclicitem ";
       $virgula = ",";
       if(trim($this->l23_codliclicitem) == null ){ 
         $this->erro_sql = " Campo Cod. Sequencial nao Informado.";
         $this->erro_campo = "l23_codliclicitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l23_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l23_obs"])){ 
       $sql  .= $virgula." l23_obs = '$this->l23_obs' ";
       $virgula = ",";
     }
     if(trim($this->l23_condpag)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l23_condpag"])){ 
       $sql  .= $virgula." l23_condpag = '$this->l23_condpag' ";
       $virgula = ",";
       if(trim($this->l23_condpag) == null ){ 
         $this->erro_sql = " Campo Condição de Pagamento nao Informado.";
         $this->erro_campo = "l23_condpag";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l23_prazo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l23_prazo"])){ 
       $sql  .= $virgula." l23_prazo = '$this->l23_prazo' ";
       $virgula = ",";
       if(trim($this->l23_prazo) == null ){ 
         $this->erro_sql = " Campo Prazo nao Informado.";
         $this->erro_campo = "l23_prazo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l23_garantia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l23_garantia"])){ 
       $sql  .= $virgula." l23_garantia = '$this->l23_garantia' ";
       $virgula = ",";
       if(trim($this->l23_garantia) == null ){ 
         $this->erro_sql = " Campo Garantia nao Informado.";
         $this->erro_campo = "l23_garantia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l23_quantcot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l23_quantcot"])){ 
       $sql  .= $virgula." l23_quantcot = $this->l23_quantcot ";
       $virgula = ",";
       if(trim($this->l23_quantcot) == null ){ 
         $this->erro_sql = " Campo Quantidade Cotada nao Informado.";
         $this->erro_campo = "l23_quantcot";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l23_valcot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l23_valcot"])){ 
       $sql  .= $virgula." l23_valcot = $this->l23_valcot ";
       $virgula = ",";
       if(trim($this->l23_valcot) == null ){ 
         $this->erro_sql = " Campo Valor Cotado nao Informado.";
         $this->erro_campo = "l23_valcot";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l23_pontuacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l23_pontuacao"])){ 
       $sql  .= $virgula." l23_pontuacao = $this->l23_pontuacao ";
       $virgula = ",";
       if(trim($this->l23_pontuacao) == null ){ 
         $this->erro_sql = " Campo Pontuação nao Informado.";
         $this->erro_campo = "l23_pontuacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($l23_codigo!=null){
       $sql .= " l23_codigo = $this->l23_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->l23_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7606,'$this->l23_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l23_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1263,7606,'".AddSlashes(pg_result($resaco,$conresaco,'l23_codigo'))."','$this->l23_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l23_codliclicitaforne"]))
           $resac = db_query("insert into db_acount values($acount,1263,7607,'".AddSlashes(pg_result($resaco,$conresaco,'l23_codliclicitaforne'))."','$this->l23_codliclicitaforne',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l23_codliclicitem"]))
           $resac = db_query("insert into db_acount values($acount,1263,7608,'".AddSlashes(pg_result($resaco,$conresaco,'l23_codliclicitem'))."','$this->l23_codliclicitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l23_obs"]))
           $resac = db_query("insert into db_acount values($acount,1263,7609,'".AddSlashes(pg_result($resaco,$conresaco,'l23_obs'))."','$this->l23_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l23_condpag"]))
           $resac = db_query("insert into db_acount values($acount,1263,7610,'".AddSlashes(pg_result($resaco,$conresaco,'l23_condpag'))."','$this->l23_condpag',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l23_prazo"]))
           $resac = db_query("insert into db_acount values($acount,1263,7611,'".AddSlashes(pg_result($resaco,$conresaco,'l23_prazo'))."','$this->l23_prazo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l23_garantia"]))
           $resac = db_query("insert into db_acount values($acount,1263,7612,'".AddSlashes(pg_result($resaco,$conresaco,'l23_garantia'))."','$this->l23_garantia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l23_quantcot"]))
           $resac = db_query("insert into db_acount values($acount,1263,7613,'".AddSlashes(pg_result($resaco,$conresaco,'l23_quantcot'))."','$this->l23_quantcot',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l23_valcot"]))
           $resac = db_query("insert into db_acount values($acount,1263,7614,'".AddSlashes(pg_result($resaco,$conresaco,'l23_valcot'))."','$this->l23_valcot',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l23_pontuacao"]))
           $resac = db_query("insert into db_acount values($acount,1263,7615,'".AddSlashes(pg_result($resaco,$conresaco,'l23_pontuacao'))."','$this->l23_pontuacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "liclicitafornitem nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->l23_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "liclicitafornitem nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->l23_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l23_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($l23_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($l23_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7606,'$l23_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1263,7606,'','".AddSlashes(pg_result($resaco,$iresaco,'l23_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1263,7607,'','".AddSlashes(pg_result($resaco,$iresaco,'l23_codliclicitaforne'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1263,7608,'','".AddSlashes(pg_result($resaco,$iresaco,'l23_codliclicitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1263,7609,'','".AddSlashes(pg_result($resaco,$iresaco,'l23_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1263,7610,'','".AddSlashes(pg_result($resaco,$iresaco,'l23_condpag'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1263,7611,'','".AddSlashes(pg_result($resaco,$iresaco,'l23_prazo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1263,7612,'','".AddSlashes(pg_result($resaco,$iresaco,'l23_garantia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1263,7613,'','".AddSlashes(pg_result($resaco,$iresaco,'l23_quantcot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1263,7614,'','".AddSlashes(pg_result($resaco,$iresaco,'l23_valcot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1263,7615,'','".AddSlashes(pg_result($resaco,$iresaco,'l23_pontuacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from liclicitafornitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($l23_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " l23_codigo = $l23_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "liclicitafornitem nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$l23_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "liclicitafornitem nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$l23_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$l23_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:liclicitafornitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $l23_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from liclicitafornitem ";
     $sql .= "      inner join liclicitem  on  liclicitem.l21_codigo = liclicitafornitem.l23_codliclicitem";
     $sql .= "      inner join liclicitaforne  on  liclicitaforne.l22_codigo = liclicitafornitem.l23_codliclicitaforne";
     $sql .= "      inner join pcprocitem  on  pcprocitem.pc81_codprocitem = liclicitem.l21_codpcprocitem";
     $sql .= "      inner join liclicita  as a on   a.l20_codigo = liclicitem.l21_codliclicita";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = liclicitaforne.l22_numcgm";
     $sql .= "      inner join liclicita  as b on   b.l20_codigo = liclicitaforne.l22_codliclicita";
     $sql2 = "";
     if($dbwhere==""){
       if($l23_codigo!=null ){
         $sql2 .= " where liclicitafornitem.l23_codigo = $l23_codigo "; 
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
   function sql_query_file ( $l23_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from liclicitafornitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($l23_codigo!=null ){
         $sql2 .= " where liclicitafornitem.l23_codigo = $l23_codigo "; 
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