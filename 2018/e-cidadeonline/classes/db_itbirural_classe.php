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

//MODULO: itbi
//CLASSE DA ENTIDADE itbirural
class cl_itbirural { 
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
   var $it18_guia = 0; 
   var $it18_frente = 0; 
   var $it18_fundos = 0; 
   var $it18_prof = 0; 
   var $it18_localimovel = null; 
   var $it18_distcidade = 0; 
   var $it18_nomelograd = null; 
   var $it18_area = 0; 
   var $it18_coordenadas = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 it18_guia = int8 = Número da guia de ITBI 
                 it18_frente = float8 = Frente 
                 it18_fundos = float8 = Fundos 
                 it18_prof = float8 = Profundidade 
                 it18_localimovel = varchar(100) = Localização do Imóvel 
                 it18_distcidade = float4 = Distância da Cidade 
                 it18_nomelograd = varchar(50) = Nome do Logradouro 
                 it18_area = float4 = Área Total 
                 it18_coordenadas = varchar(50) = Longitude/Latitude 
                 ";
   //funcao construtor da classe 
   function cl_itbirural() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("itbirural"); 
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
       $this->it18_guia = ($this->it18_guia == ""?@$GLOBALS["HTTP_POST_VARS"]["it18_guia"]:$this->it18_guia);
       $this->it18_frente = ($this->it18_frente == ""?@$GLOBALS["HTTP_POST_VARS"]["it18_frente"]:$this->it18_frente);
       $this->it18_fundos = ($this->it18_fundos == ""?@$GLOBALS["HTTP_POST_VARS"]["it18_fundos"]:$this->it18_fundos);
       $this->it18_prof = ($this->it18_prof == ""?@$GLOBALS["HTTP_POST_VARS"]["it18_prof"]:$this->it18_prof);
       $this->it18_localimovel = ($this->it18_localimovel == ""?@$GLOBALS["HTTP_POST_VARS"]["it18_localimovel"]:$this->it18_localimovel);
       $this->it18_distcidade = ($this->it18_distcidade == ""?@$GLOBALS["HTTP_POST_VARS"]["it18_distcidade"]:$this->it18_distcidade);
       $this->it18_nomelograd = ($this->it18_nomelograd == ""?@$GLOBALS["HTTP_POST_VARS"]["it18_nomelograd"]:$this->it18_nomelograd);
       $this->it18_area = ($this->it18_area == ""?@$GLOBALS["HTTP_POST_VARS"]["it18_area"]:$this->it18_area);
       $this->it18_coordenadas = ($this->it18_coordenadas == ""?@$GLOBALS["HTTP_POST_VARS"]["it18_coordenadas"]:$this->it18_coordenadas);
     }else{
       $this->it18_guia = ($this->it18_guia == ""?@$GLOBALS["HTTP_POST_VARS"]["it18_guia"]:$this->it18_guia);
     }
   }
   // funcao para inclusao
   function incluir ($it18_guia){ 
      $this->atualizacampos();
     if($this->it18_frente == null ){ 
       $this->it18_frente = "0";
     }
     if($this->it18_fundos == null ){ 
       $this->it18_fundos = "0";
     }
     if($this->it18_prof == null ){ 
       $this->it18_prof = "0";
     }
     if($this->it18_localimovel == null ){ 
       $this->erro_sql = " Campo Localização do Imóvel nao Informado.";
       $this->erro_campo = "it18_localimovel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it18_distcidade == null ){ 
       $this->it18_distcidade = "0";
     }
     if($this->it18_area == null ){ 
       $this->erro_sql = " Campo Área Total nao Informado.";
       $this->erro_campo = "it18_area";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->it18_guia = $it18_guia; 
     if(($this->it18_guia == null) || ($this->it18_guia == "") ){ 
       $this->erro_sql = " Campo it18_guia nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into itbirural(
                                       it18_guia 
                                      ,it18_frente 
                                      ,it18_fundos 
                                      ,it18_prof 
                                      ,it18_localimovel 
                                      ,it18_distcidade 
                                      ,it18_nomelograd 
                                      ,it18_area 
                                      ,it18_coordenadas 
                       )
                values (
                                $this->it18_guia 
                               ,$this->it18_frente 
                               ,$this->it18_fundos 
                               ,$this->it18_prof 
                               ,'$this->it18_localimovel' 
                               ,$this->it18_distcidade 
                               ,'$this->it18_nomelograd' 
                               ,$this->it18_area 
                               ,'$this->it18_coordenadas' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "cadastro de itbi rural ($this->it18_guia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cadastro de itbi rural já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "cadastro de itbi rural ($this->it18_guia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->it18_guia;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->it18_guia));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5840,'$this->it18_guia','I')");
       $resac = db_query("insert into db_acount values($acount,935,5840,'','".AddSlashes(pg_result($resaco,0,'it18_guia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,935,5843,'','".AddSlashes(pg_result($resaco,0,'it18_frente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,935,5844,'','".AddSlashes(pg_result($resaco,0,'it18_fundos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,935,5845,'','".AddSlashes(pg_result($resaco,0,'it18_prof'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,935,13526,'','".AddSlashes(pg_result($resaco,0,'it18_localimovel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,935,13527,'','".AddSlashes(pg_result($resaco,0,'it18_distcidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,935,13528,'','".AddSlashes(pg_result($resaco,0,'it18_nomelograd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,935,13529,'','".AddSlashes(pg_result($resaco,0,'it18_area'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,935,15481,'','".AddSlashes(pg_result($resaco,0,'it18_coordenadas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($it18_guia=null) { 
      $this->atualizacampos();
     $sql = " update itbirural set ";
     $virgula = "";
     if(trim($this->it18_guia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it18_guia"])){ 
       $sql  .= $virgula." it18_guia = $this->it18_guia ";
       $virgula = ",";
       if(trim($this->it18_guia) == null ){ 
         $this->erro_sql = " Campo Número da guia de ITBI nao Informado.";
         $this->erro_campo = "it18_guia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it18_frente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it18_frente"])){ 
        if(trim($this->it18_frente)=="" && isset($GLOBALS["HTTP_POST_VARS"]["it18_frente"])){ 
           $this->it18_frente = "0" ; 
        } 
       $sql  .= $virgula." it18_frente = $this->it18_frente ";
       $virgula = ",";
     }
     if(trim($this->it18_fundos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it18_fundos"])){ 
        if(trim($this->it18_fundos)=="" && isset($GLOBALS["HTTP_POST_VARS"]["it18_fundos"])){ 
           $this->it18_fundos = "0" ; 
        } 
       $sql  .= $virgula." it18_fundos = $this->it18_fundos ";
       $virgula = ",";
     }
     if(trim($this->it18_prof)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it18_prof"])){ 
        if(trim($this->it18_prof)=="" && isset($GLOBALS["HTTP_POST_VARS"]["it18_prof"])){ 
           $this->it18_prof = "0" ; 
        } 
       $sql  .= $virgula." it18_prof = $this->it18_prof ";
       $virgula = ",";
     }
     if(trim($this->it18_localimovel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it18_localimovel"])){ 
       $sql  .= $virgula." it18_localimovel = '$this->it18_localimovel' ";
       $virgula = ",";
       if(trim($this->it18_localimovel) == null ){ 
         $this->erro_sql = " Campo Localização do Imóvel nao Informado.";
         $this->erro_campo = "it18_localimovel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it18_distcidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it18_distcidade"])){ 
        if(trim($this->it18_distcidade)=="" && isset($GLOBALS["HTTP_POST_VARS"]["it18_distcidade"])){ 
           $this->it18_distcidade = "0" ; 
        } 
       $sql  .= $virgula." it18_distcidade = $this->it18_distcidade ";
       $virgula = ",";
     }
     if(trim($this->it18_nomelograd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it18_nomelograd"])){ 
       $sql  .= $virgula." it18_nomelograd = '$this->it18_nomelograd' ";
       $virgula = ",";
     }
     if(trim($this->it18_area)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it18_area"])){ 
       $sql  .= $virgula." it18_area = $this->it18_area ";
       $virgula = ",";
       if(trim($this->it18_area) == null ){ 
         $this->erro_sql = " Campo Área Total nao Informado.";
         $this->erro_campo = "it18_area";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it18_coordenadas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it18_coordenadas"])){ 
       $sql  .= $virgula." it18_coordenadas = '$this->it18_coordenadas' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($it18_guia!=null){
       $sql .= " it18_guia = $this->it18_guia";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->it18_guia));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5840,'$this->it18_guia','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it18_guia"]) || $this->it18_guia != "")
           $resac = db_query("insert into db_acount values($acount,935,5840,'".AddSlashes(pg_result($resaco,$conresaco,'it18_guia'))."','$this->it18_guia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it18_frente"]) || $this->it18_frente != "")
           $resac = db_query("insert into db_acount values($acount,935,5843,'".AddSlashes(pg_result($resaco,$conresaco,'it18_frente'))."','$this->it18_frente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it18_fundos"]) || $this->it18_fundos != "")
           $resac = db_query("insert into db_acount values($acount,935,5844,'".AddSlashes(pg_result($resaco,$conresaco,'it18_fundos'))."','$this->it18_fundos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it18_prof"]) || $this->it18_prof != "")
           $resac = db_query("insert into db_acount values($acount,935,5845,'".AddSlashes(pg_result($resaco,$conresaco,'it18_prof'))."','$this->it18_prof',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it18_localimovel"]) || $this->it18_localimovel != "")
           $resac = db_query("insert into db_acount values($acount,935,13526,'".AddSlashes(pg_result($resaco,$conresaco,'it18_localimovel'))."','$this->it18_localimovel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it18_distcidade"]) || $this->it18_distcidade != "")
           $resac = db_query("insert into db_acount values($acount,935,13527,'".AddSlashes(pg_result($resaco,$conresaco,'it18_distcidade'))."','$this->it18_distcidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it18_nomelograd"]) || $this->it18_nomelograd != "")
           $resac = db_query("insert into db_acount values($acount,935,13528,'".AddSlashes(pg_result($resaco,$conresaco,'it18_nomelograd'))."','$this->it18_nomelograd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it18_area"]) || $this->it18_area != "")
           $resac = db_query("insert into db_acount values($acount,935,13529,'".AddSlashes(pg_result($resaco,$conresaco,'it18_area'))."','$this->it18_area',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it18_coordenadas"]) || $this->it18_coordenadas != "")
           $resac = db_query("insert into db_acount values($acount,935,15481,'".AddSlashes(pg_result($resaco,$conresaco,'it18_coordenadas'))."','$this->it18_coordenadas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cadastro de itbi rural nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->it18_guia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cadastro de itbi rural nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->it18_guia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->it18_guia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($it18_guia=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($it18_guia));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5840,'$it18_guia','E')");
         $resac = db_query("insert into db_acount values($acount,935,5840,'','".AddSlashes(pg_result($resaco,$iresaco,'it18_guia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,935,5843,'','".AddSlashes(pg_result($resaco,$iresaco,'it18_frente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,935,5844,'','".AddSlashes(pg_result($resaco,$iresaco,'it18_fundos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,935,5845,'','".AddSlashes(pg_result($resaco,$iresaco,'it18_prof'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,935,13526,'','".AddSlashes(pg_result($resaco,$iresaco,'it18_localimovel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,935,13527,'','".AddSlashes(pg_result($resaco,$iresaco,'it18_distcidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,935,13528,'','".AddSlashes(pg_result($resaco,$iresaco,'it18_nomelograd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,935,13529,'','".AddSlashes(pg_result($resaco,$iresaco,'it18_area'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,935,15481,'','".AddSlashes(pg_result($resaco,$iresaco,'it18_coordenadas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from itbirural
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($it18_guia != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " it18_guia = $it18_guia ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cadastro de itbi rural nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$it18_guia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cadastro de itbi rural nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$it18_guia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$it18_guia;
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
        $this->erro_sql   = "Record Vazio na Tabela:itbirural";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $it18_guia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itbirural ";
     $sql .= "      inner join itbi  on  itbi.it01_guia = itbirural.it18_guia";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = itbi.it01_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = itbi.it01_coddepto";
     $sql .= "      inner join itbitransacao  on  itbitransacao.it04_codigo = itbi.it01_tipotransacao";
     $sql2 = "";
     if($dbwhere==""){
       if($it18_guia!=null ){
         $sql2 .= " where itbirural.it18_guia = $it18_guia "; 
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
   function sql_query_file ( $it18_guia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itbirural ";
     $sql2 = "";
     if($dbwhere==""){
       if($it18_guia!=null ){
         $sql2 .= " where itbirural.it18_guia = $it18_guia "; 
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
  
   function sql_query_dados( $it18_guia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itbirural ";
     $sql .= "      inner join itbidadosimovel on  itbidadosimovel.it22_itbi = itbirural.it18_guia";
     
     $sql2 = "";
     if($dbwhere==""){
       if($it18_guia!=null ){
         $sql2 .= " where itbirural.it18_guia = $it18_guia "; 
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