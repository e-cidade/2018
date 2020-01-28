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

//MODULO: Cemiterio
//CLASSE DA ENTIDADE jazigos
class cl_jazigos { 
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
   var $cm03_i_codigo = 0; 
   var $cm03_i_proprietario = 0; 
   var $cm03_c_termo = null; 
   var $cm03_d_datatermo_dia = null; 
   var $cm03_d_datatermo_mes = null; 
   var $cm03_d_datatermo_ano = null; 
   var $cm03_d_datatermo = null; 
   var $cm03_c_carta = null; 
   var $cm03_d_datacarta_dia = null; 
   var $cm03_d_datacarta_mes = null; 
   var $cm03_d_datacarta_ano = null; 
   var $cm03_d_datacarta = null; 
   var $cm03_d_aquisicao_dia = null; 
   var $cm03_d_aquisicao_mes = null; 
   var $cm03_d_aquisicao_ano = null; 
   var $cm03_d_aquisicao = null; 
   var $cm03_c_base = null; 
   var $cm03_c_estrutura = null; 
   var $cm03_c_pronto = null; 
   var $cm03_c_quadra = null; 
   var $cm03_i_lote = 0; 
   var $cm03_f_metragem1 = 0; 
   var $cm03_f_metragem2 = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 cm03_i_codigo = int4 = Código 
                 cm03_i_proprietario = int4 = Proprietário 
                 cm03_c_termo = char(10) = Termo 
                 cm03_d_datatermo = date = Data 
                 cm03_c_carta = char(10) = Carta 
                 cm03_d_datacarta = date = Data Carta 
                 cm03_d_aquisicao = date = Aquisição 
                 cm03_c_base = char(10) = Base 
                 cm03_c_estrutura = char(10) = Estrutura 
                 cm03_c_pronto = char(10) = Pronto 
                 cm03_c_quadra = char(3) = Quadra 
                 cm03_i_lote = int4 = Lote 
                 cm03_f_metragem1 = float8 = Metragem 
                 cm03_f_metragem2 = float8 = Metragem 2 
                 ";
   //funcao construtor da classe 
   function cl_jazigos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("jazigos"); 
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
       $this->cm03_i_codigo = ($this->cm03_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm03_i_codigo"]:$this->cm03_i_codigo);
       $this->cm03_i_proprietario = ($this->cm03_i_proprietario == ""?@$GLOBALS["HTTP_POST_VARS"]["cm03_i_proprietario"]:$this->cm03_i_proprietario);
       $this->cm03_c_termo = ($this->cm03_c_termo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm03_c_termo"]:$this->cm03_c_termo);
       if($this->cm03_d_datatermo == ""){
         $this->cm03_d_datatermo_dia = ($this->cm03_d_datatermo_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cm03_d_datatermo_dia"]:$this->cm03_d_datatermo_dia);
         $this->cm03_d_datatermo_mes = ($this->cm03_d_datatermo_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cm03_d_datatermo_mes"]:$this->cm03_d_datatermo_mes);
         $this->cm03_d_datatermo_ano = ($this->cm03_d_datatermo_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cm03_d_datatermo_ano"]:$this->cm03_d_datatermo_ano);
         if($this->cm03_d_datatermo_dia != ""){
            $this->cm03_d_datatermo = $this->cm03_d_datatermo_ano."-".$this->cm03_d_datatermo_mes."-".$this->cm03_d_datatermo_dia;
         }
       }
       $this->cm03_c_carta = ($this->cm03_c_carta == ""?@$GLOBALS["HTTP_POST_VARS"]["cm03_c_carta"]:$this->cm03_c_carta);
       if($this->cm03_d_datacarta == ""){
         $this->cm03_d_datacarta_dia = ($this->cm03_d_datacarta_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cm03_d_datacarta_dia"]:$this->cm03_d_datacarta_dia);
         $this->cm03_d_datacarta_mes = ($this->cm03_d_datacarta_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cm03_d_datacarta_mes"]:$this->cm03_d_datacarta_mes);
         $this->cm03_d_datacarta_ano = ($this->cm03_d_datacarta_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cm03_d_datacarta_ano"]:$this->cm03_d_datacarta_ano);
         if($this->cm03_d_datacarta_dia != ""){
            $this->cm03_d_datacarta = $this->cm03_d_datacarta_ano."-".$this->cm03_d_datacarta_mes."-".$this->cm03_d_datacarta_dia;
         }
       }
       if($this->cm03_d_aquisicao == ""){
         $this->cm03_d_aquisicao_dia = ($this->cm03_d_aquisicao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cm03_d_aquisicao_dia"]:$this->cm03_d_aquisicao_dia);
         $this->cm03_d_aquisicao_mes = ($this->cm03_d_aquisicao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cm03_d_aquisicao_mes"]:$this->cm03_d_aquisicao_mes);
         $this->cm03_d_aquisicao_ano = ($this->cm03_d_aquisicao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cm03_d_aquisicao_ano"]:$this->cm03_d_aquisicao_ano);
         if($this->cm03_d_aquisicao_dia != ""){
            $this->cm03_d_aquisicao = $this->cm03_d_aquisicao_ano."-".$this->cm03_d_aquisicao_mes."-".$this->cm03_d_aquisicao_dia;
         }
       }
       $this->cm03_c_base = ($this->cm03_c_base == ""?@$GLOBALS["HTTP_POST_VARS"]["cm03_c_base"]:$this->cm03_c_base);
       $this->cm03_c_estrutura = ($this->cm03_c_estrutura == ""?@$GLOBALS["HTTP_POST_VARS"]["cm03_c_estrutura"]:$this->cm03_c_estrutura);
       $this->cm03_c_pronto = ($this->cm03_c_pronto == ""?@$GLOBALS["HTTP_POST_VARS"]["cm03_c_pronto"]:$this->cm03_c_pronto);
       $this->cm03_c_quadra = ($this->cm03_c_quadra == ""?@$GLOBALS["HTTP_POST_VARS"]["cm03_c_quadra"]:$this->cm03_c_quadra);
       $this->cm03_i_lote = ($this->cm03_i_lote == ""?@$GLOBALS["HTTP_POST_VARS"]["cm03_i_lote"]:$this->cm03_i_lote);
       $this->cm03_f_metragem1 = ($this->cm03_f_metragem1 == ""?@$GLOBALS["HTTP_POST_VARS"]["cm03_f_metragem1"]:$this->cm03_f_metragem1);
       $this->cm03_f_metragem2 = ($this->cm03_f_metragem2 == ""?@$GLOBALS["HTTP_POST_VARS"]["cm03_f_metragem2"]:$this->cm03_f_metragem2);
     }else{
       $this->cm03_i_codigo = ($this->cm03_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm03_i_codigo"]:$this->cm03_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($cm03_i_codigo){ 
      $this->atualizacampos();
     if($this->cm03_i_proprietario == null ){ 
       $this->erro_sql = " Campo Proprietário nao Informado.";
       $this->erro_campo = "cm03_i_proprietario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm03_c_termo == null ){ 
       $this->erro_sql = " Campo Termo nao Informado.";
       $this->erro_campo = "cm03_c_termo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm03_d_datatermo == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "cm03_d_datatermo_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm03_c_carta == null ){ 
       $this->erro_sql = " Campo Carta nao Informado.";
       $this->erro_campo = "cm03_c_carta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm03_d_datacarta == null ){ 
       $this->erro_sql = " Campo Data Carta nao Informado.";
       $this->erro_campo = "cm03_d_datacarta_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm03_d_aquisicao == null ){ 
       $this->erro_sql = " Campo Aquisição nao Informado.";
       $this->erro_campo = "cm03_d_aquisicao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm03_c_base == null ){ 
       $this->erro_sql = " Campo Base nao Informado.";
       $this->erro_campo = "cm03_c_base";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm03_c_estrutura == null ){ 
       $this->erro_sql = " Campo Estrutura nao Informado.";
       $this->erro_campo = "cm03_c_estrutura";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm03_c_pronto == null ){ 
       $this->erro_sql = " Campo Pronto nao Informado.";
       $this->erro_campo = "cm03_c_pronto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm03_c_quadra == null ){ 
       $this->erro_sql = " Campo Quadra nao Informado.";
       $this->erro_campo = "cm03_c_quadra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm03_i_lote == null ){ 
       $this->erro_sql = " Campo Lote nao Informado.";
       $this->erro_campo = "cm03_i_lote";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm03_f_metragem1 == null ){ 
       $this->erro_sql = " Campo Metragem nao Informado.";
       $this->erro_campo = "cm03_f_metragem1";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm03_f_metragem2 == null ){ 
       $this->erro_sql = " Campo Metragem 2 nao Informado.";
       $this->erro_campo = "cm03_f_metragem2";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($cm03_i_codigo == "" || $cm03_i_codigo == null ){
       $result = db_query("select nextval('jazigos_cm03_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: jazigos_cm03_i_codigo_seq do campo: cm03_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->cm03_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from jazigos_cm03_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $cm03_i_codigo)){
         $this->erro_sql = " Campo cm03_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cm03_i_codigo = $cm03_i_codigo; 
       }
     }
     if(($this->cm03_i_codigo == null) || ($this->cm03_i_codigo == "") ){ 
       $this->erro_sql = " Campo cm03_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into jazigos(
                                       cm03_i_codigo 
                                      ,cm03_i_proprietario 
                                      ,cm03_c_termo 
                                      ,cm03_d_datatermo 
                                      ,cm03_c_carta 
                                      ,cm03_d_datacarta 
                                      ,cm03_d_aquisicao 
                                      ,cm03_c_base 
                                      ,cm03_c_estrutura 
                                      ,cm03_c_pronto 
                                      ,cm03_c_quadra 
                                      ,cm03_i_lote 
                                      ,cm03_f_metragem1 
                                      ,cm03_f_metragem2 
                       )
                values (
                                $this->cm03_i_codigo 
                               ,$this->cm03_i_proprietario 
                               ,'$this->cm03_c_termo' 
                               ,".($this->cm03_d_datatermo == "null" || $this->cm03_d_datatermo == ""?"null":"'".$this->cm03_d_datatermo."'")." 
                               ,'$this->cm03_c_carta' 
                               ,".($this->cm03_d_datacarta == "null" || $this->cm03_d_datacarta == ""?"null":"'".$this->cm03_d_datacarta."'")." 
                               ,".($this->cm03_d_aquisicao == "null" || $this->cm03_d_aquisicao == ""?"null":"'".$this->cm03_d_aquisicao."'")." 
                               ,'$this->cm03_c_base' 
                               ,'$this->cm03_c_estrutura' 
                               ,'$this->cm03_c_pronto' 
                               ,'$this->cm03_c_quadra' 
                               ,$this->cm03_i_lote 
                               ,$this->cm03_f_metragem1 
                               ,$this->cm03_f_metragem2 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Jazigos ($this->cm03_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Jazigos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Jazigos ($this->cm03_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm03_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cm03_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10330,'$this->cm03_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1789,10330,'','".AddSlashes(pg_result($resaco,0,'cm03_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1789,10331,'','".AddSlashes(pg_result($resaco,0,'cm03_i_proprietario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1789,10332,'','".AddSlashes(pg_result($resaco,0,'cm03_c_termo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1789,10333,'','".AddSlashes(pg_result($resaco,0,'cm03_d_datatermo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1789,10334,'','".AddSlashes(pg_result($resaco,0,'cm03_c_carta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1789,10335,'','".AddSlashes(pg_result($resaco,0,'cm03_d_datacarta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1789,10336,'','".AddSlashes(pg_result($resaco,0,'cm03_d_aquisicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1789,10337,'','".AddSlashes(pg_result($resaco,0,'cm03_c_base'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1789,10338,'','".AddSlashes(pg_result($resaco,0,'cm03_c_estrutura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1789,10339,'','".AddSlashes(pg_result($resaco,0,'cm03_c_pronto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1789,10340,'','".AddSlashes(pg_result($resaco,0,'cm03_c_quadra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1789,10341,'','".AddSlashes(pg_result($resaco,0,'cm03_i_lote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1789,10342,'','".AddSlashes(pg_result($resaco,0,'cm03_f_metragem1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1789,10343,'','".AddSlashes(pg_result($resaco,0,'cm03_f_metragem2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($cm03_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update jazigos set ";
     $virgula = "";
     if(trim($this->cm03_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm03_i_codigo"])){ 
       $sql  .= $virgula." cm03_i_codigo = $this->cm03_i_codigo ";
       $virgula = ",";
       if(trim($this->cm03_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "cm03_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm03_i_proprietario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm03_i_proprietario"])){ 
       $sql  .= $virgula." cm03_i_proprietario = $this->cm03_i_proprietario ";
       $virgula = ",";
       if(trim($this->cm03_i_proprietario) == null ){ 
         $this->erro_sql = " Campo Proprietário nao Informado.";
         $this->erro_campo = "cm03_i_proprietario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm03_c_termo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm03_c_termo"])){ 
       $sql  .= $virgula." cm03_c_termo = '$this->cm03_c_termo' ";
       $virgula = ",";
       if(trim($this->cm03_c_termo) == null ){ 
         $this->erro_sql = " Campo Termo nao Informado.";
         $this->erro_campo = "cm03_c_termo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm03_d_datatermo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm03_d_datatermo_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cm03_d_datatermo_dia"] !="") ){ 
       $sql  .= $virgula." cm03_d_datatermo = '$this->cm03_d_datatermo' ";
       $virgula = ",";
       if(trim($this->cm03_d_datatermo) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "cm03_d_datatermo_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["cm03_d_datatermo_dia"])){ 
         $sql  .= $virgula." cm03_d_datatermo = null ";
         $virgula = ",";
         if(trim($this->cm03_d_datatermo) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "cm03_d_datatermo_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->cm03_c_carta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm03_c_carta"])){ 
       $sql  .= $virgula." cm03_c_carta = '$this->cm03_c_carta' ";
       $virgula = ",";
       if(trim($this->cm03_c_carta) == null ){ 
         $this->erro_sql = " Campo Carta nao Informado.";
         $this->erro_campo = "cm03_c_carta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm03_d_datacarta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm03_d_datacarta_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cm03_d_datacarta_dia"] !="") ){ 
       $sql  .= $virgula." cm03_d_datacarta = '$this->cm03_d_datacarta' ";
       $virgula = ",";
       if(trim($this->cm03_d_datacarta) == null ){ 
         $this->erro_sql = " Campo Data Carta nao Informado.";
         $this->erro_campo = "cm03_d_datacarta_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["cm03_d_datacarta_dia"])){ 
         $sql  .= $virgula." cm03_d_datacarta = null ";
         $virgula = ",";
         if(trim($this->cm03_d_datacarta) == null ){ 
           $this->erro_sql = " Campo Data Carta nao Informado.";
           $this->erro_campo = "cm03_d_datacarta_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->cm03_d_aquisicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm03_d_aquisicao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cm03_d_aquisicao_dia"] !="") ){ 
       $sql  .= $virgula." cm03_d_aquisicao = '$this->cm03_d_aquisicao' ";
       $virgula = ",";
       if(trim($this->cm03_d_aquisicao) == null ){ 
         $this->erro_sql = " Campo Aquisição nao Informado.";
         $this->erro_campo = "cm03_d_aquisicao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["cm03_d_aquisicao_dia"])){ 
         $sql  .= $virgula." cm03_d_aquisicao = null ";
         $virgula = ",";
         if(trim($this->cm03_d_aquisicao) == null ){ 
           $this->erro_sql = " Campo Aquisição nao Informado.";
           $this->erro_campo = "cm03_d_aquisicao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->cm03_c_base)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm03_c_base"])){ 
       $sql  .= $virgula." cm03_c_base = '$this->cm03_c_base' ";
       $virgula = ",";
       if(trim($this->cm03_c_base) == null ){ 
         $this->erro_sql = " Campo Base nao Informado.";
         $this->erro_campo = "cm03_c_base";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm03_c_estrutura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm03_c_estrutura"])){ 
       $sql  .= $virgula." cm03_c_estrutura = '$this->cm03_c_estrutura' ";
       $virgula = ",";
       if(trim($this->cm03_c_estrutura) == null ){ 
         $this->erro_sql = " Campo Estrutura nao Informado.";
         $this->erro_campo = "cm03_c_estrutura";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm03_c_pronto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm03_c_pronto"])){ 
       $sql  .= $virgula." cm03_c_pronto = '$this->cm03_c_pronto' ";
       $virgula = ",";
       if(trim($this->cm03_c_pronto) == null ){ 
         $this->erro_sql = " Campo Pronto nao Informado.";
         $this->erro_campo = "cm03_c_pronto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm03_c_quadra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm03_c_quadra"])){ 
       $sql  .= $virgula." cm03_c_quadra = '$this->cm03_c_quadra' ";
       $virgula = ",";
       if(trim($this->cm03_c_quadra) == null ){ 
         $this->erro_sql = " Campo Quadra nao Informado.";
         $this->erro_campo = "cm03_c_quadra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm03_i_lote)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm03_i_lote"])){ 
       $sql  .= $virgula." cm03_i_lote = $this->cm03_i_lote ";
       $virgula = ",";
       if(trim($this->cm03_i_lote) == null ){ 
         $this->erro_sql = " Campo Lote nao Informado.";
         $this->erro_campo = "cm03_i_lote";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm03_f_metragem1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm03_f_metragem1"])){ 
       $sql  .= $virgula." cm03_f_metragem1 = $this->cm03_f_metragem1 ";
       $virgula = ",";
       if(trim($this->cm03_f_metragem1) == null ){ 
         $this->erro_sql = " Campo Metragem nao Informado.";
         $this->erro_campo = "cm03_f_metragem1";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm03_f_metragem2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm03_f_metragem2"])){ 
       $sql  .= $virgula." cm03_f_metragem2 = $this->cm03_f_metragem2 ";
       $virgula = ",";
       if(trim($this->cm03_f_metragem2) == null ){ 
         $this->erro_sql = " Campo Metragem 2 nao Informado.";
         $this->erro_campo = "cm03_f_metragem2";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($cm03_i_codigo!=null){
       $sql .= " cm03_i_codigo = $this->cm03_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cm03_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10330,'$this->cm03_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm03_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1789,10330,'".AddSlashes(pg_result($resaco,$conresaco,'cm03_i_codigo'))."','$this->cm03_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm03_i_proprietario"]))
           $resac = db_query("insert into db_acount values($acount,1789,10331,'".AddSlashes(pg_result($resaco,$conresaco,'cm03_i_proprietario'))."','$this->cm03_i_proprietario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm03_c_termo"]))
           $resac = db_query("insert into db_acount values($acount,1789,10332,'".AddSlashes(pg_result($resaco,$conresaco,'cm03_c_termo'))."','$this->cm03_c_termo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm03_d_datatermo"]))
           $resac = db_query("insert into db_acount values($acount,1789,10333,'".AddSlashes(pg_result($resaco,$conresaco,'cm03_d_datatermo'))."','$this->cm03_d_datatermo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm03_c_carta"]))
           $resac = db_query("insert into db_acount values($acount,1789,10334,'".AddSlashes(pg_result($resaco,$conresaco,'cm03_c_carta'))."','$this->cm03_c_carta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm03_d_datacarta"]))
           $resac = db_query("insert into db_acount values($acount,1789,10335,'".AddSlashes(pg_result($resaco,$conresaco,'cm03_d_datacarta'))."','$this->cm03_d_datacarta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm03_d_aquisicao"]))
           $resac = db_query("insert into db_acount values($acount,1789,10336,'".AddSlashes(pg_result($resaco,$conresaco,'cm03_d_aquisicao'))."','$this->cm03_d_aquisicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm03_c_base"]))
           $resac = db_query("insert into db_acount values($acount,1789,10337,'".AddSlashes(pg_result($resaco,$conresaco,'cm03_c_base'))."','$this->cm03_c_base',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm03_c_estrutura"]))
           $resac = db_query("insert into db_acount values($acount,1789,10338,'".AddSlashes(pg_result($resaco,$conresaco,'cm03_c_estrutura'))."','$this->cm03_c_estrutura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm03_c_pronto"]))
           $resac = db_query("insert into db_acount values($acount,1789,10339,'".AddSlashes(pg_result($resaco,$conresaco,'cm03_c_pronto'))."','$this->cm03_c_pronto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm03_c_quadra"]))
           $resac = db_query("insert into db_acount values($acount,1789,10340,'".AddSlashes(pg_result($resaco,$conresaco,'cm03_c_quadra'))."','$this->cm03_c_quadra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm03_i_lote"]))
           $resac = db_query("insert into db_acount values($acount,1789,10341,'".AddSlashes(pg_result($resaco,$conresaco,'cm03_i_lote'))."','$this->cm03_i_lote',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm03_f_metragem1"]))
           $resac = db_query("insert into db_acount values($acount,1789,10342,'".AddSlashes(pg_result($resaco,$conresaco,'cm03_f_metragem1'))."','$this->cm03_f_metragem1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm03_f_metragem2"]))
           $resac = db_query("insert into db_acount values($acount,1789,10343,'".AddSlashes(pg_result($resaco,$conresaco,'cm03_f_metragem2'))."','$this->cm03_f_metragem2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Jazigos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm03_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Jazigos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm03_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm03_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($cm03_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cm03_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10330,'$cm03_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1789,10330,'','".AddSlashes(pg_result($resaco,$iresaco,'cm03_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1789,10331,'','".AddSlashes(pg_result($resaco,$iresaco,'cm03_i_proprietario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1789,10332,'','".AddSlashes(pg_result($resaco,$iresaco,'cm03_c_termo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1789,10333,'','".AddSlashes(pg_result($resaco,$iresaco,'cm03_d_datatermo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1789,10334,'','".AddSlashes(pg_result($resaco,$iresaco,'cm03_c_carta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1789,10335,'','".AddSlashes(pg_result($resaco,$iresaco,'cm03_d_datacarta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1789,10336,'','".AddSlashes(pg_result($resaco,$iresaco,'cm03_d_aquisicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1789,10337,'','".AddSlashes(pg_result($resaco,$iresaco,'cm03_c_base'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1789,10338,'','".AddSlashes(pg_result($resaco,$iresaco,'cm03_c_estrutura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1789,10339,'','".AddSlashes(pg_result($resaco,$iresaco,'cm03_c_pronto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1789,10340,'','".AddSlashes(pg_result($resaco,$iresaco,'cm03_c_quadra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1789,10341,'','".AddSlashes(pg_result($resaco,$iresaco,'cm03_i_lote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1789,10342,'','".AddSlashes(pg_result($resaco,$iresaco,'cm03_f_metragem1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1789,10343,'','".AddSlashes(pg_result($resaco,$iresaco,'cm03_f_metragem2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from jazigos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cm03_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cm03_i_codigo = $cm03_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Jazigos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cm03_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Jazigos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cm03_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cm03_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:jazigos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $cm03_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from jazigos ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = jazigos.cm03_i_proprietario";
     $sql2 = "";
     if($dbwhere==""){
       if($cm03_i_codigo!=null ){
         $sql2 .= " where jazigos.cm03_i_codigo = $cm03_i_codigo "; 
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
   function sql_query_file ( $cm03_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from jazigos ";
     $sql2 = "";
     if($dbwhere==""){
       if($cm03_i_codigo!=null ){
         $sql2 .= " where jazigos.cm03_i_codigo = $cm03_i_codigo "; 
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