<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//MODULO: compras
//CLASSE DA ENTIDADE solicitem
class cl_solicitem { 
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
   var $pc11_codigo = 0; 
   var $pc11_numero = 0; 
   var $pc11_seq = 0; 
   var $pc11_quant = 0; 
   var $pc11_vlrun = 0; 
   var $pc11_prazo = null; 
   var $pc11_pgto = null; 
   var $pc11_resum = null; 
   var $pc11_just = null; 
   var $pc11_liberado = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc11_codigo = int8 = Código do registro 
                 pc11_numero = int4 = Solicitacao 
                 pc11_seq = int4 = Sequencial 
                 pc11_quant = float8 = Qtde solicitada 
                 pc11_vlrun = float8 = Vlr unit. aprox 
                 pc11_prazo = text = Prazo de entrega 
                 pc11_pgto = text = condicoes de pagamento 
                 pc11_resum = text = resumo do item 
                 pc11_just = text = justificativa para compra 
                 pc11_liberado = bool = Liberar para contabilidade 
                 ";
   //funcao construtor da classe 
   function cl_solicitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("solicitem"); 
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
       $this->pc11_codigo = ($this->pc11_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc11_codigo"]:$this->pc11_codigo);
       $this->pc11_numero = ($this->pc11_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["pc11_numero"]:$this->pc11_numero);
       $this->pc11_seq = ($this->pc11_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["pc11_seq"]:$this->pc11_seq);
       $this->pc11_quant = ($this->pc11_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["pc11_quant"]:$this->pc11_quant);
       $this->pc11_vlrun = ($this->pc11_vlrun == ""?@$GLOBALS["HTTP_POST_VARS"]["pc11_vlrun"]:$this->pc11_vlrun);
       $this->pc11_prazo = ($this->pc11_prazo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc11_prazo"]:$this->pc11_prazo);
       $this->pc11_pgto = ($this->pc11_pgto == ""?@$GLOBALS["HTTP_POST_VARS"]["pc11_pgto"]:$this->pc11_pgto);
       $this->pc11_resum = ($this->pc11_resum == ""?@$GLOBALS["HTTP_POST_VARS"]["pc11_resum"]:$this->pc11_resum);
       $this->pc11_just = ($this->pc11_just == ""?@$GLOBALS["HTTP_POST_VARS"]["pc11_just"]:$this->pc11_just);
       $this->pc11_liberado = ($this->pc11_liberado == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc11_liberado"]:$this->pc11_liberado);
     }else{
       $this->pc11_codigo = ($this->pc11_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc11_codigo"]:$this->pc11_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($pc11_codigo){ 
      $this->atualizacampos();
     if($this->pc11_numero == null ){ 
       $this->erro_sql = " Campo Solicitacao nao Informado.";
       $this->erro_campo = "pc11_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc11_seq == null ){ 
       $this->erro_sql = " Campo Sequencial nao Informado.";
       $this->erro_campo = "pc11_seq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc11_quant == null ){ 
       $this->erro_sql = " Campo Qtde solicitada nao Informado.";
       $this->erro_campo = "pc11_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc11_vlrun == null ){ 
       $this->erro_sql = " Campo Vlr unit. aprox nao Informado.";
       $this->erro_campo = "pc11_vlrun";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc11_liberado == null ){ 
       $this->erro_sql = " Campo Liberar para contabilidade nao Informado.";
       $this->erro_campo = "pc11_liberado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($pc11_codigo == "" || $pc11_codigo == null ){
       $result = @db_query("select nextval('solicitem_pc11_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: solicitem_pc11_codigo_seq do campo: pc11_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pc11_codigo = pg_result($result,0,0); 
     }else{
       $result = @db_query("select last_value from solicitem_pc11_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc11_codigo)){
         $this->erro_sql = " Campo pc11_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc11_codigo = $pc11_codigo; 
       }
     }
     if(($this->pc11_codigo == null) || ($this->pc11_codigo == "") ){ 
       $this->erro_sql = " Campo pc11_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into solicitem(
                                       pc11_codigo 
                                      ,pc11_numero 
                                      ,pc11_seq 
                                      ,pc11_quant 
                                      ,pc11_vlrun 
                                      ,pc11_prazo 
                                      ,pc11_pgto 
                                      ,pc11_resum 
                                      ,pc11_just 
                                      ,pc11_liberado 
                       )
                values (
                                $this->pc11_codigo 
                               ,$this->pc11_numero 
                               ,$this->pc11_seq 
                               ,$this->pc11_quant 
                               ,$this->pc11_vlrun 
                               ,'$this->pc11_prazo' 
                               ,'$this->pc11_pgto' 
                               ,'$this->pc11_resum' 
                               ,'$this->pc11_just' 
                               ,'$this->pc11_liberado' 
                      )";
     $result = @db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "itens da solicitacao de compras ($this->pc11_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "itens da solicitacao de compras já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "itens da solicitacao de compras ($this->pc11_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc11_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc11_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountkey values($acount,5558,'$this->pc11_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,870,5558,'','".AddSlashes(pg_result($resaco,0,'pc11_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,870,5548,'','".AddSlashes(pg_result($resaco,0,'pc11_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,870,5549,'','".AddSlashes(pg_result($resaco,0,'pc11_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,870,5551,'','".AddSlashes(pg_result($resaco,0,'pc11_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,870,5552,'','".AddSlashes(pg_result($resaco,0,'pc11_vlrun'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,870,5553,'','".AddSlashes(pg_result($resaco,0,'pc11_prazo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,870,5554,'','".AddSlashes(pg_result($resaco,0,'pc11_pgto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,870,5555,'','".AddSlashes(pg_result($resaco,0,'pc11_resum'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,870,5556,'','".AddSlashes(pg_result($resaco,0,'pc11_just'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,870,5557,'','".AddSlashes(pg_result($resaco,0,'pc11_liberado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc11_codigo=null) { 
      $this->atualizacampos();
     $sql = " update solicitem set ";
     $virgula = "";
     if(trim($this->pc11_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc11_codigo"])){ 
       $sql  .= $virgula." pc11_codigo = $this->pc11_codigo ";
       $virgula = ",";
       if(trim($this->pc11_codigo) == null ){ 
         $this->erro_sql = " Campo Código do registro nao Informado.";
         $this->erro_campo = "pc11_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc11_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc11_numero"])){ 
       $sql  .= $virgula." pc11_numero = $this->pc11_numero ";
       $virgula = ",";
       if(trim($this->pc11_numero) == null ){ 
         $this->erro_sql = " Campo Solicitacao nao Informado.";
         $this->erro_campo = "pc11_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc11_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc11_seq"])){ 
       $sql  .= $virgula." pc11_seq = $this->pc11_seq ";
       $virgula = ",";
       if(trim($this->pc11_seq) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "pc11_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc11_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc11_quant"])){ 
       $sql  .= $virgula." pc11_quant = $this->pc11_quant ";
       $virgula = ",";
       if(trim($this->pc11_quant) == null ){ 
         $this->erro_sql = " Campo Qtde solicitada nao Informado.";
         $this->erro_campo = "pc11_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc11_vlrun)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc11_vlrun"])){ 
       $sql  .= $virgula." pc11_vlrun = $this->pc11_vlrun ";
       $virgula = ",";
       if(trim($this->pc11_vlrun) == null ){ 
         $this->erro_sql = " Campo Vlr unit. aprox nao Informado.";
         $this->erro_campo = "pc11_vlrun";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc11_prazo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc11_prazo"])){ 
       $sql  .= $virgula." pc11_prazo = '$this->pc11_prazo' ";
       $virgula = ",";
     }
     if(trim($this->pc11_pgto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc11_pgto"])){ 
       $sql  .= $virgula." pc11_pgto = '$this->pc11_pgto' ";
       $virgula = ",";
     }
     if(trim($this->pc11_resum)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc11_resum"])){ 
       $sql  .= $virgula." pc11_resum = '$this->pc11_resum' ";
       $virgula = ",";
     }
     if(trim($this->pc11_just)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc11_just"])){ 
       $sql  .= $virgula." pc11_just = '$this->pc11_just' ";
       $virgula = ",";
     }
     if(trim($this->pc11_liberado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc11_liberado"])){ 
       $sql  .= $virgula." pc11_liberado = '$this->pc11_liberado' ";
       $virgula = ",";
       if(trim($this->pc11_liberado) == null ){ 
         $this->erro_sql = " Campo Liberar para contabilidade nao Informado.";
         $this->erro_campo = "pc11_liberado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pc11_codigo!=null){
       $sql .= " pc11_codigo = $this->pc11_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc11_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountkey values($acount,5558,'$this->pc11_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc11_codigo"]))
           $resac = db_query("insert into db_acount values($acount,870,5558,'".AddSlashes(pg_result($resaco,$conresaco,'pc11_codigo'))."','$this->pc11_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc11_numero"]))
           $resac = db_query("insert into db_acount values($acount,870,5548,'".AddSlashes(pg_result($resaco,$conresaco,'pc11_numero'))."','$this->pc11_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc11_seq"]))
           $resac = db_query("insert into db_acount values($acount,870,5549,'".AddSlashes(pg_result($resaco,$conresaco,'pc11_seq'))."','$this->pc11_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc11_quant"]))
           $resac = db_query("insert into db_acount values($acount,870,5551,'".AddSlashes(pg_result($resaco,$conresaco,'pc11_quant'))."','$this->pc11_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc11_vlrun"]))
           $resac = db_query("insert into db_acount values($acount,870,5552,'".AddSlashes(pg_result($resaco,$conresaco,'pc11_vlrun'))."','$this->pc11_vlrun',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc11_prazo"]))
           $resac = db_query("insert into db_acount values($acount,870,5553,'".AddSlashes(pg_result($resaco,$conresaco,'pc11_prazo'))."','$this->pc11_prazo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc11_pgto"]))
           $resac = db_query("insert into db_acount values($acount,870,5554,'".AddSlashes(pg_result($resaco,$conresaco,'pc11_pgto'))."','$this->pc11_pgto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc11_resum"]))
           $resac = db_query("insert into db_acount values($acount,870,5555,'".AddSlashes(pg_result($resaco,$conresaco,'pc11_resum'))."','$this->pc11_resum',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc11_just"]))
           $resac = db_query("insert into db_acount values($acount,870,5556,'".AddSlashes(pg_result($resaco,$conresaco,'pc11_just'))."','$this->pc11_just',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc11_liberado"]))
           $resac = db_query("insert into db_acount values($acount,870,5557,'".AddSlashes(pg_result($resaco,$conresaco,'pc11_liberado'))."','$this->pc11_liberado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "itens da solicitacao de compras nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc11_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "itens da solicitacao de compras nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc11_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc11_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc11_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc11_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountkey values($acount,5558,'".pg_result($resaco,$iresaco,'pc11_codigo')."','E')");
         $resac = db_query("insert into db_acount values($acount,870,5558,'','".AddSlashes(pg_result($resaco,$iresaco,'pc11_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,870,5548,'','".AddSlashes(pg_result($resaco,$iresaco,'pc11_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,870,5549,'','".AddSlashes(pg_result($resaco,$iresaco,'pc11_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,870,5551,'','".AddSlashes(pg_result($resaco,$iresaco,'pc11_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,870,5552,'','".AddSlashes(pg_result($resaco,$iresaco,'pc11_vlrun'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,870,5553,'','".AddSlashes(pg_result($resaco,$iresaco,'pc11_prazo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,870,5554,'','".AddSlashes(pg_result($resaco,$iresaco,'pc11_pgto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,870,5555,'','".AddSlashes(pg_result($resaco,$iresaco,'pc11_resum'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,870,5556,'','".AddSlashes(pg_result($resaco,$iresaco,'pc11_just'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,870,5557,'','".AddSlashes(pg_result($resaco,$iresaco,'pc11_liberado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from solicitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc11_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc11_codigo = $pc11_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "itens da solicitacao de compras nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc11_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "itens da solicitacao de compras nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc11_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc11_codigo;
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
     $result = @db_query($sql);
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
        $this->erro_sql   = "Record Vazio na Tabela:solicitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query_rel ( $pc11_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from solicitem ";
     $sql .= "      left  join solicitemunid  on  solicitemunid.pc17_codigo = solicitem.pc11_codigo";     
     $sql .= "      left  join matunid  on  matunid.m61_codmatunid = solicitemunid.pc17_unid";
     $sql .= "      left  join solicitempcmater  on  solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
     $sql .= "      left  join pcmater  on pcmater.pc01_codmater = solicitempcmater.pc16_codmater";
     $sql .= "      left  join pcsubgrupo  on  pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo";
     $sql .= "      left  join pctipo  on  pctipo.pc05_codtipo = pcsubgrupo.pc04_codtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($pc11_codigo!=null ){
         $sql2 .= " where solicitem.pc11_codigo = $pc11_codigo "; 
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
   function sql_query ( $pc11_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from solicitem ";
     $sql .= "      inner join solicita  on  solicita.pc10_numero = solicitem.pc11_numero";
     $sql .= "      inner join db_config  on  db_config.codigo = solicita.pc10_instit";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = solicita.pc10_depto";
     $sql2 = "";
     if($dbwhere==""){
       if($pc11_codigo!=null ){
         $sql2 .= " where solicitem.pc11_codigo = $pc11_codigo "; 
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
   function sql_query_file ( $pc11_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from solicitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc11_codigo!=null ){
         $sql2 .= " where solicitem.pc11_codigo = $pc11_codigo "; 
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
   function sql_query_pcmater ( $pc11_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from solicitem ";
     $sql .= "      inner join solicita on  solicita.pc10_numero = solicitem.pc11_numero";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = solicita.pc10_depto";
     $sql .= "      left  join solicitempcmater  on  solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
     $sql .= "      left  join pcmater  on  pcmater.pc01_codmater = solicitempcmater.pc16_codmater";
     $sql2 = "";
     if($dbwhere==""){
       if($pc11_codigo!=null ){
         $sql2 .= " where solicitem.pc11_codigo = $pc11_codigo ";
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