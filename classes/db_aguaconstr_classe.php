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

//MODULO: agua
//CLASSE DA ENTIDADE aguaconstr
class cl_aguaconstr { 
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
   var $x11_codconstr = 0; 
   var $x11_matric = 0; 
   var $x11_area = 0; 
   var $x11_pavimento = null; 
   var $x11_numero = 0; 
   var $x11_qtdfamilia = 0; 
   var $x11_qtdpessoas = 0; 
   var $x11_complemento = null; 
   var $x11_tipo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 x11_codconstr = int4 = Codigo 
                 x11_matric = int4 = Matrícula 
                 x11_area = float4 = Área Construída 
                 x11_pavimento = varchar(20) = Pavimento 
                 x11_numero = int4 = Construção 
                 x11_qtdfamilia = int4 = Qtd. Família 
                 x11_qtdpessoas = int4 = Qtd. Pessoas 
                 x11_complemento = varchar(30) = Complemento 
                 x11_tipo = char(1) = Tipo 
                 ";
   //funcao construtor da classe 
   function cl_aguaconstr() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aguaconstr"); 
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
       $this->x11_codconstr = ($this->x11_codconstr == ""?@$GLOBALS["HTTP_POST_VARS"]["x11_codconstr"]:$this->x11_codconstr);
       $this->x11_matric = ($this->x11_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["x11_matric"]:$this->x11_matric);
       $this->x11_area = ($this->x11_area == ""?@$GLOBALS["HTTP_POST_VARS"]["x11_area"]:$this->x11_area);
       $this->x11_pavimento = ($this->x11_pavimento == ""?@$GLOBALS["HTTP_POST_VARS"]["x11_pavimento"]:$this->x11_pavimento);
       $this->x11_numero = ($this->x11_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["x11_numero"]:$this->x11_numero);
       $this->x11_qtdfamilia = ($this->x11_qtdfamilia == ""?@$GLOBALS["HTTP_POST_VARS"]["x11_qtdfamilia"]:$this->x11_qtdfamilia);
       $this->x11_qtdpessoas = ($this->x11_qtdpessoas == ""?@$GLOBALS["HTTP_POST_VARS"]["x11_qtdpessoas"]:$this->x11_qtdpessoas);
       $this->x11_complemento = ($this->x11_complemento == ""?@$GLOBALS["HTTP_POST_VARS"]["x11_complemento"]:$this->x11_complemento);
       $this->x11_tipo = ($this->x11_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["x11_tipo"]:$this->x11_tipo);
     }else{
       $this->x11_codconstr = ($this->x11_codconstr == ""?@$GLOBALS["HTTP_POST_VARS"]["x11_codconstr"]:$this->x11_codconstr);
     }
   }
   // funcao para inclusao
   function incluir ($x11_codconstr){ 
      $this->atualizacampos();
     if($this->x11_matric == null ){ 
       $this->erro_sql = " Campo Matrícula nao Informado.";
       $this->erro_campo = "x11_matric";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x11_area == null ){ 
       $this->erro_sql = " Campo Área Construída nao Informado.";
       $this->erro_campo = "x11_area";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x11_pavimento == null ){ 
       $this->erro_sql = " Campo Pavimento nao Informado.";
       $this->erro_campo = "x11_pavimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x11_numero == null ){ 
       $this->erro_sql = " Campo Construção nao Informado.";
       $this->erro_campo = "x11_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x11_qtdfamilia == null ){ 
       $this->erro_sql = " Campo Qtd. Família nao Informado.";
       $this->erro_campo = "x11_qtdfamilia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x11_qtdpessoas == null ){ 
       $this->erro_sql = " Campo Qtd. Pessoas nao Informado.";
       $this->erro_campo = "x11_qtdpessoas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x11_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "x11_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($x11_codconstr == "" || $x11_codconstr == null ){
       $result = db_query("select nextval('aguaconstr_x11_seq_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: aguaconstr_x11_seq_seq do campo: x11_codconstr"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->x11_codconstr = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from aguaconstr_x11_seq_seq");
       if(($result != false) && (pg_result($result,0,0) < $x11_codconstr)){
         $this->erro_sql = " Campo x11_codconstr maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->x11_codconstr = $x11_codconstr; 
       }
     }
     if(($this->x11_codconstr == null) || ($this->x11_codconstr == "") ){ 
       $this->erro_sql = " Campo x11_codconstr nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aguaconstr(
                                       x11_codconstr 
                                      ,x11_matric 
                                      ,x11_area 
                                      ,x11_pavimento 
                                      ,x11_numero 
                                      ,x11_qtdfamilia 
                                      ,x11_qtdpessoas 
                                      ,x11_complemento 
                                      ,x11_tipo 
                       )
                values (
                                $this->x11_codconstr 
                               ,$this->x11_matric 
                               ,$this->x11_area 
                               ,'$this->x11_pavimento' 
                               ,$this->x11_numero 
                               ,$this->x11_qtdfamilia 
                               ,$this->x11_qtdpessoas 
                               ,'$this->x11_complemento' 
                               ,'$this->x11_tipo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "aguaconstr ($this->x11_codconstr) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "aguaconstr já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "aguaconstr ($this->x11_codconstr) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x11_codconstr;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->x11_codconstr));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8405,'$this->x11_codconstr','I')");
       $resac = db_query("insert into db_acount values($acount,1422,8405,'','".AddSlashes(pg_result($resaco,0,'x11_codconstr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1422,8406,'','".AddSlashes(pg_result($resaco,0,'x11_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1422,8407,'','".AddSlashes(pg_result($resaco,0,'x11_area'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1422,8408,'','".AddSlashes(pg_result($resaco,0,'x11_pavimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1422,8409,'','".AddSlashes(pg_result($resaco,0,'x11_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1422,8410,'','".AddSlashes(pg_result($resaco,0,'x11_qtdfamilia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1422,8411,'','".AddSlashes(pg_result($resaco,0,'x11_qtdpessoas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1422,8444,'','".AddSlashes(pg_result($resaco,0,'x11_complemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1422,9824,'','".AddSlashes(pg_result($resaco,0,'x11_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($x11_codconstr=null) { 
      $this->atualizacampos();
     $sql = " update aguaconstr set ";
     $virgula = "";
     if(trim($this->x11_codconstr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x11_codconstr"])){ 
       $sql  .= $virgula." x11_codconstr = $this->x11_codconstr ";
       $virgula = ",";
       if(trim($this->x11_codconstr) == null ){ 
         $this->erro_sql = " Campo Codigo nao Informado.";
         $this->erro_campo = "x11_codconstr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x11_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x11_matric"])){ 
       $sql  .= $virgula." x11_matric = $this->x11_matric ";
       $virgula = ",";
       if(trim($this->x11_matric) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "x11_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x11_area)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x11_area"])){ 
       $sql  .= $virgula." x11_area = $this->x11_area ";
       $virgula = ",";
       if(trim($this->x11_area) == null ){ 
         $this->erro_sql = " Campo Área Construída nao Informado.";
         $this->erro_campo = "x11_area";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x11_pavimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x11_pavimento"])){ 
       $sql  .= $virgula." x11_pavimento = '$this->x11_pavimento' ";
       $virgula = ",";
       if(trim($this->x11_pavimento) == null ){ 
         $this->erro_sql = " Campo Pavimento nao Informado.";
         $this->erro_campo = "x11_pavimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x11_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x11_numero"])){ 
       $sql  .= $virgula." x11_numero = $this->x11_numero ";
       $virgula = ",";
       if(trim($this->x11_numero) == null ){ 
         $this->erro_sql = " Campo Construção nao Informado.";
         $this->erro_campo = "x11_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x11_qtdfamilia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x11_qtdfamilia"])){ 
       $sql  .= $virgula." x11_qtdfamilia = $this->x11_qtdfamilia ";
       $virgula = ",";
       if(trim($this->x11_qtdfamilia) == null ){ 
         $this->erro_sql = " Campo Qtd. Família nao Informado.";
         $this->erro_campo = "x11_qtdfamilia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x11_qtdpessoas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x11_qtdpessoas"])){ 
       $sql  .= $virgula." x11_qtdpessoas = $this->x11_qtdpessoas ";
       $virgula = ",";
       if(trim($this->x11_qtdpessoas) == null ){ 
         $this->erro_sql = " Campo Qtd. Pessoas nao Informado.";
         $this->erro_campo = "x11_qtdpessoas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x11_complemento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x11_complemento"])){ 
       $sql  .= $virgula." x11_complemento = '$this->x11_complemento' ";
       $virgula = ",";
     }
     if(trim($this->x11_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x11_tipo"])){ 
       $sql  .= $virgula." x11_tipo = '$this->x11_tipo' ";
       $virgula = ",";
       if(trim($this->x11_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "x11_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($x11_codconstr!=null){
       $sql .= " x11_codconstr = $this->x11_codconstr";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->x11_codconstr));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8405,'$this->x11_codconstr','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x11_codconstr"]))
           $resac = db_query("insert into db_acount values($acount,1422,8405,'".AddSlashes(pg_result($resaco,$conresaco,'x11_codconstr'))."','$this->x11_codconstr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x11_matric"]))
           $resac = db_query("insert into db_acount values($acount,1422,8406,'".AddSlashes(pg_result($resaco,$conresaco,'x11_matric'))."','$this->x11_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x11_area"]))
           $resac = db_query("insert into db_acount values($acount,1422,8407,'".AddSlashes(pg_result($resaco,$conresaco,'x11_area'))."','$this->x11_area',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x11_pavimento"]))
           $resac = db_query("insert into db_acount values($acount,1422,8408,'".AddSlashes(pg_result($resaco,$conresaco,'x11_pavimento'))."','$this->x11_pavimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x11_numero"]))
           $resac = db_query("insert into db_acount values($acount,1422,8409,'".AddSlashes(pg_result($resaco,$conresaco,'x11_numero'))."','$this->x11_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x11_qtdfamilia"]))
           $resac = db_query("insert into db_acount values($acount,1422,8410,'".AddSlashes(pg_result($resaco,$conresaco,'x11_qtdfamilia'))."','$this->x11_qtdfamilia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x11_qtdpessoas"]))
           $resac = db_query("insert into db_acount values($acount,1422,8411,'".AddSlashes(pg_result($resaco,$conresaco,'x11_qtdpessoas'))."','$this->x11_qtdpessoas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x11_complemento"]))
           $resac = db_query("insert into db_acount values($acount,1422,8444,'".AddSlashes(pg_result($resaco,$conresaco,'x11_complemento'))."','$this->x11_complemento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x11_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1422,9824,'".AddSlashes(pg_result($resaco,$conresaco,'x11_tipo'))."','$this->x11_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguaconstr nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->x11_codconstr;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguaconstr nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->x11_codconstr;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x11_codconstr;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($x11_codconstr=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($x11_codconstr));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8405,'$x11_codconstr','E')");
         $resac = db_query("insert into db_acount values($acount,1422,8405,'','".AddSlashes(pg_result($resaco,$iresaco,'x11_codconstr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1422,8406,'','".AddSlashes(pg_result($resaco,$iresaco,'x11_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1422,8407,'','".AddSlashes(pg_result($resaco,$iresaco,'x11_area'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1422,8408,'','".AddSlashes(pg_result($resaco,$iresaco,'x11_pavimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1422,8409,'','".AddSlashes(pg_result($resaco,$iresaco,'x11_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1422,8410,'','".AddSlashes(pg_result($resaco,$iresaco,'x11_qtdfamilia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1422,8411,'','".AddSlashes(pg_result($resaco,$iresaco,'x11_qtdpessoas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1422,8444,'','".AddSlashes(pg_result($resaco,$iresaco,'x11_complemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1422,9824,'','".AddSlashes(pg_result($resaco,$iresaco,'x11_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from aguaconstr
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($x11_codconstr != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " x11_codconstr = $x11_codconstr ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguaconstr nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$x11_codconstr;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguaconstr nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$x11_codconstr;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$x11_codconstr;
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
        $this->erro_sql   = "Record Vazio na Tabela:aguaconstr";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $x11_codconstr=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguaconstr ";
     $sql .= "      inner join aguabase  on  aguabase.x01_matric = aguaconstr.x11_matric";
     $sql .= "      inner join bairro  on  bairro.j13_codi = aguabase.x01_codbairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = aguabase.x01_codrua";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = aguabase.x01_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($x11_codconstr!=null ){
         $sql2 .= " where aguaconstr.x11_codconstr = $x11_codconstr "; 
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
   function sql_query_file ( $x11_codconstr=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguaconstr ";
     $sql2 = "";
     if($dbwhere==""){
       if($x11_codconstr!=null ){
         $sql2 .= " where aguaconstr.x11_codconstr = $x11_codconstr "; 
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