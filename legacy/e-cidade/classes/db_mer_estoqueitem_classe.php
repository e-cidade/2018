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

//MODULO: merenda
//CLASSE DA ENTIDADE mer_estoqueitem
class cl_mer_estoqueitem { 
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
   var $me19_i_codigo = 0; 
   var $me19_f_quant = 0; 
   var $me19_f_valor = 0; 
   var $me19_f_quantatend = 0; 
   var $me19_d_data_dia = null; 
   var $me19_d_data_mes = null; 
   var $me19_d_data_ano = null; 
   var $me19_d_data = null; 
   var $me19_i_merestoque = 0; 
   var $me19_i_matrequi = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 me19_i_codigo = int4 = Código 
                 me19_f_quant = float4 = Quantidade 
                 me19_f_valor = float4 = Valor 
                 me19_f_quantatend = float4 = Quantidade Atendida 
                 me19_d_data = date = Data 
                 me19_i_merestoque = int4 = Estoque 
                 me19_i_matrequi = int4 = Requisição 
                 ";
   //funcao construtor da classe 
   function cl_mer_estoqueitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("mer_estoqueitem"); 
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
       $this->me19_i_codigo = ($this->me19_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me19_i_codigo"]:$this->me19_i_codigo);
       $this->me19_f_quant = ($this->me19_f_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["me19_f_quant"]:$this->me19_f_quant);
       $this->me19_f_valor = ($this->me19_f_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["me19_f_valor"]:$this->me19_f_valor);
       $this->me19_f_quantatend = ($this->me19_f_quantatend == ""?@$GLOBALS["HTTP_POST_VARS"]["me19_f_quantatend"]:$this->me19_f_quantatend);
       if($this->me19_d_data == ""){
         $this->me19_d_data_dia = ($this->me19_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["me19_d_data_dia"]:$this->me19_d_data_dia);
         $this->me19_d_data_mes = ($this->me19_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["me19_d_data_mes"]:$this->me19_d_data_mes);
         $this->me19_d_data_ano = ($this->me19_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["me19_d_data_ano"]:$this->me19_d_data_ano);
         if($this->me19_d_data_dia != ""){
            $this->me19_d_data = $this->me19_d_data_ano."-".$this->me19_d_data_mes."-".$this->me19_d_data_dia;
         }
       }
       $this->me19_i_merestoque = ($this->me19_i_merestoque == ""?@$GLOBALS["HTTP_POST_VARS"]["me19_i_merestoque"]:$this->me19_i_merestoque);
       $this->me19_i_matrequi = ($this->me19_i_matrequi == ""?@$GLOBALS["HTTP_POST_VARS"]["me19_i_matrequi"]:$this->me19_i_matrequi);
     }else{
       $this->me19_i_codigo = ($this->me19_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me19_i_codigo"]:$this->me19_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($me19_i_codigo){ 
      $this->atualizacampos();
     if($this->me19_f_quant == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "me19_f_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me19_f_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "me19_f_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me19_f_quantatend == null ){ 
       $this->erro_sql = " Campo Quantidade Atendida nao Informado.";
       $this->erro_campo = "me19_f_quantatend";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me19_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "me19_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me19_i_merestoque == null ){ 
       $this->erro_sql = " Campo Estoque nao Informado.";
       $this->erro_campo = "me19_i_merestoque";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me19_i_matrequi == null ){ 
       $this->me19_i_matrequi = "0";
     }
     if($me19_i_codigo == "" || $me19_i_codigo == null ){
       $result = db_query("select nextval('merestoqueitem_me19_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: merestoqueitem_me19_codigo_seq do campo: me19_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->me19_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from merestoqueitem_me19_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $me19_i_codigo)){
         $this->erro_sql = " Campo me19_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->me19_i_codigo = $me19_i_codigo; 
       }
     }
     if(($this->me19_i_codigo == null) || ($this->me19_i_codigo == "") ){ 
       $this->erro_sql = " Campo me19_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into mer_estoqueitem(
                                       me19_i_codigo 
                                      ,me19_f_quant 
                                      ,me19_f_valor 
                                      ,me19_f_quantatend 
                                      ,me19_d_data 
                                      ,me19_i_merestoque 
                                      ,me19_i_matrequi 
                       )
                values (
                                $this->me19_i_codigo 
                               ,$this->me19_f_quant 
                               ,$this->me19_f_valor 
                               ,$this->me19_f_quantatend 
                               ,".($this->me19_d_data == "null" || $this->me19_d_data == ""?"null":"'".$this->me19_d_data."'")." 
                               ,$this->me19_i_merestoque 
                               ,$this->me19_i_matrequi 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "mer_estoqueitem ($this->me19_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "mer_estoqueitem já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "mer_estoqueitem ($this->me19_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me19_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->me19_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12741,'$this->me19_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2229,12741,'','".AddSlashes(pg_result($resaco,0,'me19_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2229,12742,'','".AddSlashes(pg_result($resaco,0,'me19_f_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2229,12743,'','".AddSlashes(pg_result($resaco,0,'me19_f_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2229,12744,'','".AddSlashes(pg_result($resaco,0,'me19_f_quantatend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2229,12745,'','".AddSlashes(pg_result($resaco,0,'me19_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2229,12746,'','".AddSlashes(pg_result($resaco,0,'me19_i_merestoque'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2229,13422,'','".AddSlashes(pg_result($resaco,0,'me19_i_matrequi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($me19_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update mer_estoqueitem set ";
     $virgula = "";
     if(trim($this->me19_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me19_i_codigo"])){ 
       $sql  .= $virgula." me19_i_codigo = $this->me19_i_codigo ";
       $virgula = ",";
       if(trim($this->me19_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "me19_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me19_f_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me19_f_quant"])){ 
       $sql  .= $virgula." me19_f_quant = $this->me19_f_quant ";
       $virgula = ",";
       if(trim($this->me19_f_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "me19_f_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me19_f_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me19_f_valor"])){ 
       $sql  .= $virgula." me19_f_valor = $this->me19_f_valor ";
       $virgula = ",";
       if(trim($this->me19_f_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "me19_f_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me19_f_quantatend)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me19_f_quantatend"])){ 
       $sql  .= $virgula." me19_f_quantatend = $this->me19_f_quantatend ";
       $virgula = ",";
       if(trim($this->me19_f_quantatend) == null ){ 
         $this->erro_sql = " Campo Quantidade Atendida nao Informado.";
         $this->erro_campo = "me19_f_quantatend";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me19_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me19_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["me19_d_data_dia"] !="") ){ 
       $sql  .= $virgula." me19_d_data = '$this->me19_d_data' ";
       $virgula = ",";
       if(trim($this->me19_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "me19_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["me19_d_data_dia"])){ 
         $sql  .= $virgula." me19_d_data = null ";
         $virgula = ",";
         if(trim($this->me19_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "me19_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->me19_i_merestoque)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me19_i_merestoque"])){ 
       $sql  .= $virgula." me19_i_merestoque = $this->me19_i_merestoque ";
       $virgula = ",";
       if(trim($this->me19_i_merestoque) == null ){ 
         $this->erro_sql = " Campo Estoque nao Informado.";
         $this->erro_campo = "me19_i_merestoque";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me19_i_matrequi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me19_i_matrequi"])){ 
        if(trim($this->me19_i_matrequi)=="" && isset($GLOBALS["HTTP_POST_VARS"]["me19_i_matrequi"])){ 
           $this->me19_i_matrequi = "0" ; 
        } 
       $sql  .= $virgula." me19_i_matrequi = $this->me19_i_matrequi ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($me19_i_codigo!=null){
       $sql .= " me19_i_codigo = $this->me19_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->me19_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12741,'$this->me19_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me19_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,2229,12741,'".AddSlashes(pg_result($resaco,$conresaco,'me19_i_codigo'))."','$this->me19_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me19_f_quant"]))
           $resac = db_query("insert into db_acount values($acount,2229,12742,'".AddSlashes(pg_result($resaco,$conresaco,'me19_f_quant'))."','$this->me19_f_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me19_f_valor"]))
           $resac = db_query("insert into db_acount values($acount,2229,12743,'".AddSlashes(pg_result($resaco,$conresaco,'me19_f_valor'))."','$this->me19_f_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me19_f_quantatend"]))
           $resac = db_query("insert into db_acount values($acount,2229,12744,'".AddSlashes(pg_result($resaco,$conresaco,'me19_f_quantatend'))."','$this->me19_f_quantatend',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me19_d_data"]))
           $resac = db_query("insert into db_acount values($acount,2229,12745,'".AddSlashes(pg_result($resaco,$conresaco,'me19_d_data'))."','$this->me19_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me19_i_merestoque"]))
           $resac = db_query("insert into db_acount values($acount,2229,12746,'".AddSlashes(pg_result($resaco,$conresaco,'me19_i_merestoque'))."','$this->me19_i_merestoque',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me19_i_matrequi"]))
           $resac = db_query("insert into db_acount values($acount,2229,13422,'".AddSlashes(pg_result($resaco,$conresaco,'me19_i_matrequi'))."','$this->me19_i_matrequi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mer_estoqueitem nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->me19_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mer_estoqueitem nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->me19_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me19_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($me19_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($me19_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12741,'$me19_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2229,12741,'','".AddSlashes(pg_result($resaco,$iresaco,'me19_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2229,12742,'','".AddSlashes(pg_result($resaco,$iresaco,'me19_f_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2229,12743,'','".AddSlashes(pg_result($resaco,$iresaco,'me19_f_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2229,12744,'','".AddSlashes(pg_result($resaco,$iresaco,'me19_f_quantatend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2229,12745,'','".AddSlashes(pg_result($resaco,$iresaco,'me19_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2229,12746,'','".AddSlashes(pg_result($resaco,$iresaco,'me19_i_merestoque'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2229,13422,'','".AddSlashes(pg_result($resaco,$iresaco,'me19_i_matrequi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from mer_estoqueitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($me19_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " me19_i_codigo = $me19_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mer_estoqueitem nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$me19_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mer_estoqueitem nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$me19_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$me19_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:mer_estoqueitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $me19_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_estoqueitem ";
     $sql .= "      left  join matrequi  on  matrequi.m40_codigo = mer_estoqueitem.me19_i_matrequi";
     $sql .= "      inner join mer_estoque  on  mer_estoque.me18_i_codigo = mer_estoqueitem.me19_i_merestoque";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = matrequi.m40_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = matrequi.m40_depto";
     $sql .= "      inner join db_almox  on  db_almox.m91_codigo = matrequi.m40_almox";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = mer_estoque.me18_i_codmater";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = mer_estoque.me18_i_escola";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = mer_estoque.me18_i_calendario";
     $sql2 = "";
     if($dbwhere==""){
       if($me19_i_codigo!=null ){
         $sql2 .= " where mer_estoqueitem.me19_i_codigo = $me19_i_codigo "; 
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
   function sql_query_file ( $me19_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_estoqueitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($me19_i_codigo!=null ){
         $sql2 .= " where mer_estoqueitem.me19_i_codigo = $me19_i_codigo "; 
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