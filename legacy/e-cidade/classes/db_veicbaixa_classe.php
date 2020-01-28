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

//MODULO: veiculos
//CLASSE DA ENTIDADE veicbaixa
class cl_veicbaixa { 
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
   var $ve04_codigo = 0; 
   var $ve04_veiculo = 0; 
   var $ve04_data_dia = null; 
   var $ve04_data_mes = null; 
   var $ve04_data_ano = null; 
   var $ve04_data = null; 
   var $ve04_hora = null; 
   var $ve04_usuario = 0; 
   var $ve04_motivo = null; 
   var $ve04_veiccadtipobaixa = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ve04_codigo = int4 = Código da Baixa 
                 ve04_veiculo = int4 = Veiculo 
                 ve04_data = date = Data da Baixa 
                 ve04_hora = char(5) = Hora da Baixa 
                 ve04_usuario = int4 = Usuário 
                 ve04_motivo = text = Motivo 
                 ve04_veiccadtipobaixa = int4 = Tipo de baixa 
                 ";
   //funcao construtor da classe 
   function cl_veicbaixa() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("veicbaixa"); 
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
       $this->ve04_codigo = ($this->ve04_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve04_codigo"]:$this->ve04_codigo);
       $this->ve04_veiculo = ($this->ve04_veiculo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve04_veiculo"]:$this->ve04_veiculo);
       if($this->ve04_data == ""){
         $this->ve04_data_dia = ($this->ve04_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ve04_data_dia"]:$this->ve04_data_dia);
         $this->ve04_data_mes = ($this->ve04_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ve04_data_mes"]:$this->ve04_data_mes);
         $this->ve04_data_ano = ($this->ve04_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ve04_data_ano"]:$this->ve04_data_ano);
         if($this->ve04_data_dia != ""){
            $this->ve04_data = $this->ve04_data_ano."-".$this->ve04_data_mes."-".$this->ve04_data_dia;
         }
       }
       $this->ve04_hora = ($this->ve04_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["ve04_hora"]:$this->ve04_hora);
       $this->ve04_usuario = ($this->ve04_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ve04_usuario"]:$this->ve04_usuario);
       $this->ve04_motivo = ($this->ve04_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve04_motivo"]:$this->ve04_motivo);
       $this->ve04_veiccadtipobaixa = ($this->ve04_veiccadtipobaixa == ""?@$GLOBALS["HTTP_POST_VARS"]["ve04_veiccadtipobaixa"]:$this->ve04_veiccadtipobaixa);
     }else{
       $this->ve04_codigo = ($this->ve04_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve04_codigo"]:$this->ve04_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ve04_codigo){ 
      $this->atualizacampos();
     if($this->ve04_veiculo == null ){ 
       $this->erro_sql = " Campo Veiculo nao Informado.";
       $this->erro_campo = "ve04_veiculo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve04_data == null ){ 
       $this->erro_sql = " Campo Data da Baixa nao Informado.";
       $this->erro_campo = "ve04_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve04_hora == null ){ 
       $this->erro_sql = " Campo Hora da Baixa nao Informado.";
       $this->erro_campo = "ve04_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve04_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "ve04_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve04_motivo == null ){ 
       $this->erro_sql = " Campo Motivo nao Informado.";
       $this->erro_campo = "ve04_motivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve04_veiccadtipobaixa == null ){ 
       $this->erro_sql = " Campo Tipo de baixa nao Informado.";
       $this->erro_campo = "ve04_veiccadtipobaixa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ve04_codigo == "" || $ve04_codigo == null ){
       $result = db_query("select nextval('veicbaixa_ve04_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: veicbaixa_ve04_codigo_seq do campo: ve04_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ve04_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from veicbaixa_ve04_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ve04_codigo)){
         $this->erro_sql = " Campo ve04_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ve04_codigo = $ve04_codigo; 
       }
     }
     if(($this->ve04_codigo == null) || ($this->ve04_codigo == "") ){ 
       $this->erro_sql = " Campo ve04_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into veicbaixa(
                                       ve04_codigo 
                                      ,ve04_veiculo 
                                      ,ve04_data 
                                      ,ve04_hora 
                                      ,ve04_usuario 
                                      ,ve04_motivo 
                                      ,ve04_veiccadtipobaixa 
                       )
                values (
                                $this->ve04_codigo 
                               ,$this->ve04_veiculo 
                               ,".($this->ve04_data == "null" || $this->ve04_data == ""?"null":"'".$this->ve04_data."'")." 
                               ,'$this->ve04_hora' 
                               ,$this->ve04_usuario 
                               ,'$this->ve04_motivo' 
                               ,$this->ve04_veiccadtipobaixa 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Baixa dos Veículos ($this->ve04_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Baixa dos Veículos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Baixa dos Veículos ($this->ve04_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve04_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ve04_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9274,'$this->ve04_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1594,9274,'','".AddSlashes(pg_result($resaco,0,'ve04_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1594,9279,'','".AddSlashes(pg_result($resaco,0,'ve04_veiculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1594,9275,'','".AddSlashes(pg_result($resaco,0,'ve04_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1594,9276,'','".AddSlashes(pg_result($resaco,0,'ve04_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1594,9277,'','".AddSlashes(pg_result($resaco,0,'ve04_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1594,9278,'','".AddSlashes(pg_result($resaco,0,'ve04_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1594,11087,'','".AddSlashes(pg_result($resaco,0,'ve04_veiccadtipobaixa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ve04_codigo=null) { 
      $this->atualizacampos();
     $sql = " update veicbaixa set ";
     $virgula = "";
     if(trim($this->ve04_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve04_codigo"])){ 
       $sql  .= $virgula." ve04_codigo = $this->ve04_codigo ";
       $virgula = ",";
       if(trim($this->ve04_codigo) == null ){ 
         $this->erro_sql = " Campo Código da Baixa nao Informado.";
         $this->erro_campo = "ve04_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve04_veiculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve04_veiculo"])){ 
       $sql  .= $virgula." ve04_veiculo = $this->ve04_veiculo ";
       $virgula = ",";
       if(trim($this->ve04_veiculo) == null ){ 
         $this->erro_sql = " Campo Veiculo nao Informado.";
         $this->erro_campo = "ve04_veiculo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve04_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve04_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ve04_data_dia"] !="") ){ 
       $sql  .= $virgula." ve04_data = '$this->ve04_data' ";
       $virgula = ",";
       if(trim($this->ve04_data) == null ){ 
         $this->erro_sql = " Campo Data da Baixa nao Informado.";
         $this->erro_campo = "ve04_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ve04_data_dia"])){ 
         $sql  .= $virgula." ve04_data = null ";
         $virgula = ",";
         if(trim($this->ve04_data) == null ){ 
           $this->erro_sql = " Campo Data da Baixa nao Informado.";
           $this->erro_campo = "ve04_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ve04_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve04_hora"])){ 
       $sql  .= $virgula." ve04_hora = '$this->ve04_hora' ";
       $virgula = ",";
       if(trim($this->ve04_hora) == null ){ 
         $this->erro_sql = " Campo Hora da Baixa nao Informado.";
         $this->erro_campo = "ve04_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve04_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve04_usuario"])){ 
       $sql  .= $virgula." ve04_usuario = $this->ve04_usuario ";
       $virgula = ",";
       if(trim($this->ve04_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "ve04_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve04_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve04_motivo"])){ 
       $sql  .= $virgula." ve04_motivo = '$this->ve04_motivo' ";
       $virgula = ",";
       if(trim($this->ve04_motivo) == null ){ 
         $this->erro_sql = " Campo Motivo nao Informado.";
         $this->erro_campo = "ve04_motivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve04_veiccadtipobaixa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve04_veiccadtipobaixa"])){ 
       $sql  .= $virgula." ve04_veiccadtipobaixa = $this->ve04_veiccadtipobaixa ";
       $virgula = ",";
       if(trim($this->ve04_veiccadtipobaixa) == null ){ 
         $this->erro_sql = " Campo Tipo de baixa nao Informado.";
         $this->erro_campo = "ve04_veiccadtipobaixa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ve04_codigo!=null){
       $sql .= " ve04_codigo = $this->ve04_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ve04_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9274,'$this->ve04_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve04_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1594,9274,'".AddSlashes(pg_result($resaco,$conresaco,'ve04_codigo'))."','$this->ve04_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve04_veiculo"]))
           $resac = db_query("insert into db_acount values($acount,1594,9279,'".AddSlashes(pg_result($resaco,$conresaco,'ve04_veiculo'))."','$this->ve04_veiculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve04_data"]))
           $resac = db_query("insert into db_acount values($acount,1594,9275,'".AddSlashes(pg_result($resaco,$conresaco,'ve04_data'))."','$this->ve04_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve04_hora"]))
           $resac = db_query("insert into db_acount values($acount,1594,9276,'".AddSlashes(pg_result($resaco,$conresaco,'ve04_hora'))."','$this->ve04_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve04_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1594,9277,'".AddSlashes(pg_result($resaco,$conresaco,'ve04_usuario'))."','$this->ve04_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve04_motivo"]))
           $resac = db_query("insert into db_acount values($acount,1594,9278,'".AddSlashes(pg_result($resaco,$conresaco,'ve04_motivo'))."','$this->ve04_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve04_veiccadtipobaixa"]))
           $resac = db_query("insert into db_acount values($acount,1594,11087,'".AddSlashes(pg_result($resaco,$conresaco,'ve04_veiccadtipobaixa'))."','$this->ve04_veiccadtipobaixa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Baixa dos Veículos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve04_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Baixa dos Veículos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve04_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve04_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ve04_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ve04_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9274,'$ve04_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1594,9274,'','".AddSlashes(pg_result($resaco,$iresaco,'ve04_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1594,9279,'','".AddSlashes(pg_result($resaco,$iresaco,'ve04_veiculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1594,9275,'','".AddSlashes(pg_result($resaco,$iresaco,'ve04_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1594,9276,'','".AddSlashes(pg_result($resaco,$iresaco,'ve04_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1594,9277,'','".AddSlashes(pg_result($resaco,$iresaco,'ve04_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1594,9278,'','".AddSlashes(pg_result($resaco,$iresaco,'ve04_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1594,11087,'','".AddSlashes(pg_result($resaco,$iresaco,'ve04_veiccadtipobaixa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from veicbaixa
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ve04_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ve04_codigo = $ve04_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Baixa dos Veículos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ve04_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Baixa dos Veículos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ve04_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ve04_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:veicbaixa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ve04_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicbaixa ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = veicbaixa.ve04_usuario";
     $sql .= "      inner join veiculos  on  veiculos.ve01_codigo = veicbaixa.ve04_veiculo";
     $sql .= "      inner join veiccadtipobaixa  on  veiccadtipobaixa.ve12_sequencial = veicbaixa.ve04_veiccadtipobaixa";
     $sql .= "      inner join veiccentral    on veiccentral.ve40_veiculos      = veiculos.ve01_codigo";
     $sql .= "      inner join veiccadcentral on veiccadcentral.ve36_sequencial = veiccentral.ve40_veiccadcentral";
     $sql .= "      inner join db_depart      on db_depart.coddepto             = veiccadcentral.ve36_coddepto";
     $sql .= "      inner join ceplocalidades  on  ceplocalidades.cp05_codlocalidades = veiculos.ve01_ceplocalidades";
     $sql .= "      inner join veiccadtipo  as a on   a.ve20_codigo = veiculos.ve01_veiccadtipo";
     $sql .= "      inner join veiccadmarca  on  veiccadmarca.ve21_codigo = veiculos.ve01_veiccadmarca";
     $sql .= "      inner join veiccadmodelo  on  veiccadmodelo.ve22_codigo = veiculos.ve01_veiccadmodelo";
     $sql .= "      inner join veiccadcor  on  veiccadcor.ve23_codigo = veiculos.ve01_veiccadcor";
     $sql .= "      inner join veiccadtipocapacidade  on  veiccadtipocapacidade.ve24_codigo = veiculos.ve01_veiccadtipocapacidade";
     $sql .= "      inner join veiccadcategcnh  on  veiccadcategcnh.ve30_codigo = veiculos.ve01_veiccadcategcnh";
     $sql .= "      inner join veiccadproced  on  veiccadproced.ve25_codigo = veiculos.ve01_veiccadproced";
     $sql .= "      inner join veiccadpotencia  on  veiccadpotencia.ve31_codigo = veiculos.ve01_veiccadpotencia";
     $sql .= "      inner join veiccadcateg  as b on   b.ve32_codigo = veiculos.ve01_veiccadcateg";
     $sql .= "      inner join veictipoabast  on  veictipoabast.ve07_sequencial = veiculos.ve01_veictipoabast";
     $sql2 = "";
     if($dbwhere==""){
       if($ve04_codigo!=null ){
         $sql2 .= " where veicbaixa.ve04_codigo = $ve04_codigo "; 
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
   function sql_query_file ( $ve04_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicbaixa ";
     $sql2 = "";
     if($dbwhere==""){
       if($ve04_codigo!=null ){
         $sql2 .= " where veicbaixa.ve04_codigo = $ve04_codigo "; 
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